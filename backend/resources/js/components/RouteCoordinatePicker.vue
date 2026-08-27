<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

const props = defineProps({
    originName: {
        type: String,
        default: "",
    },
    destinationName: {
        type: String,
        default: "",
    },
    originLat: {
        type: [Number, String],
        default: null,
    },
    originLng: {
        type: [Number, String],
        default: null,
    },
    destinationLat: {
        type: [Number, String],
        default: null,
    },
    destinationLng: {
        type: [Number, String],
        default: null,
    },
    waypoints: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["change"]);

const mapContainer = ref(null);
const mapInstance = ref(null);
const overlayGroup = ref(null);
const activeMode = ref("origin");
const loading = ref(false);
const statusMessage = ref(
    "Pilih mode, lalu klik peta untuk menaruh titik koordinat.",
);

const state = ref({
    originLat: normalizeCoordinate(props.originLat),
    originLng: normalizeCoordinate(props.originLng),
    destinationLat: normalizeCoordinate(props.destinationLat),
    destinationLng: normalizeCoordinate(props.destinationLng),
    waypoints: normalizeWaypoints(props.waypoints),
});

const defaultCenter = [-2.5489, 118.0149];

function normalizeCoordinate(value) {
    if (value === null || value === undefined || value === "") {
        return null;
    }

    return Number(value);
}

function normalizeWaypoints(list) {
    if (!Array.isArray(list)) {
        return [];
    }

    return list
        .map((waypoint, index) => ({
            name: waypoint?.name || `Waypoint ${index + 1}`,
            lat: normalizeCoordinate(waypoint?.lat),
            lng: normalizeCoordinate(waypoint?.lng),
        }))
        .filter(
            (waypoint) =>
                waypoint.name || waypoint.lat !== null || waypoint.lng !== null,
        );
}

function emitChange() {
    emit("change", {
        origin_lat: state.value.originLat,
        origin_lng: state.value.originLng,
        destination_lat: state.value.destinationLat,
        destination_lng: state.value.destinationLng,
        waypoints: state.value.waypoints,
    });
}

function getOriginPoint() {
    if (state.value.originLat !== null && state.value.originLng !== null) {
        return [state.value.originLat, state.value.originLng];
    }

    return null;
}

function getDestinationPoint() {
    if (
        state.value.destinationLat !== null &&
        state.value.destinationLng !== null
    ) {
        return [state.value.destinationLat, state.value.destinationLng];
    }

    return null;
}

function buildRouteCoordinates() {
    const coordinates = [];

    const originPoint = getOriginPoint();
    if (originPoint) {
        coordinates.push(originPoint);
    }

    state.value.waypoints.forEach((waypoint) => {
        if (waypoint.lat !== null && waypoint.lng !== null) {
            coordinates.push([waypoint.lat, waypoint.lng]);
        }
    });

    const destinationPoint = getDestinationPoint();
    if (destinationPoint) {
        coordinates.push(destinationPoint);
    }

    return coordinates;
}

function renderRoute() {
    if (!mapInstance.value || !overlayGroup.value) {
        return;
    }

    overlayGroup.value.clearLayers();

    const routeCoordinates = buildRouteCoordinates();
    const markers = [];

    const originPoint = getOriginPoint();
    if (originPoint) {
        const originMarker = L.marker(originPoint, { draggable: true }).addTo(
            overlayGroup.value,
        );
        originMarker.bindPopup(
            `<strong>Asal</strong><br>${props.originName || "Belum diisi"}`,
        );
        originMarker.on("dragend", (event) => {
            const { lat, lng } = event.target.getLatLng();
            state.value.originLat = lat;
            state.value.originLng = lng;
            emitChange();
            renderRoute();
        });
        markers.push(originMarker);
    }

    state.value.waypoints.forEach((waypoint, index) => {
        if (waypoint.lat === null || waypoint.lng === null) {
            return;
        }

        const waypointMarker = L.marker([waypoint.lat, waypoint.lng], {
            draggable: true,
        }).addTo(overlayGroup.value);
        waypointMarker.bindPopup(
            `<strong>Waypoint ${index + 1}</strong><br>${waypoint.name || "Perhentian"}`,
        );
        waypointMarker.on("dragend", (event) => {
            const { lat, lng } = event.target.getLatLng();
            state.value.waypoints[index].lat = lat;
            state.value.waypoints[index].lng = lng;
            emitChange();
            renderRoute();
        });
        markers.push(waypointMarker);
    });

    const destinationPoint = getDestinationPoint();
    if (destinationPoint) {
        const destinationMarker = L.marker(destinationPoint, {
            draggable: true,
        }).addTo(overlayGroup.value);
        destinationMarker.bindPopup(
            `<strong>Tujuan</strong><br>${props.destinationName || "Belum diisi"}`,
        );
        destinationMarker.on("dragend", (event) => {
            const { lat, lng } = event.target.getLatLng();
            state.value.destinationLat = lat;
            state.value.destinationLng = lng;
            emitChange();
            renderRoute();
        });
        markers.push(destinationMarker);
    }

    if (routeCoordinates.length > 1) {
        const polyline = L.polyline(routeCoordinates, {
            color: "#e11d48",
            weight: 4,
            opacity: 0.9,
            lineCap: "round",
            lineJoin: "round",
        }).addTo(overlayGroup.value);

        mapInstance.value.fitBounds(polyline.getBounds(), {
            padding: [50, 50],
        });
    } else if (routeCoordinates.length === 1) {
        mapInstance.value.setView(routeCoordinates[0], 10);
    }
}

async function geocodeLocation(target) {
    const query =
        target === "origin" ? props.originName : props.destinationName;

    if (!query || !query.trim()) {
        statusMessage.value =
            target === "origin"
                ? "Isi nama asal terlebih dahulu."
                : "Isi nama tujuan terlebih dahulu.";
        return;
    }

    loading.value = true;
    statusMessage.value = `Mencari koordinat ${target === "origin" ? "asal" : "tujuan"}...`;

    try {
        const { data } = await axios.get(route("admin.routes.geocode"), {
            params: { query },
            headers: { Accept: "application/json" },
        });

        if (!data?.success || !data?.data) {
            throw new Error(data?.message || "Koordinat tidak ditemukan.");
        }

        const lat = Number(data.data.lat);
        const lng = Number(data.data.lng);

        if (target === "origin") {
            state.value.originLat = lat;
            state.value.originLng = lng;
        } else {
            state.value.destinationLat = lat;
            state.value.destinationLng = lng;
        }

        statusMessage.value = `Koordinat ${target === "origin" ? "asal" : "tujuan"} berhasil ditemukan.`;
        emitChange();
        renderRoute();
    } catch (error) {
        statusMessage.value =
            error?.response?.data?.message ||
            error?.message ||
            "Gagal mengambil koordinat.";
    } finally {
        loading.value = false;
    }
}

function setMode(mode) {
    activeMode.value = mode;
    const labelMap = {
        origin: "Klik peta untuk menaruh titik asal.",
        destination: "Klik peta untuk menaruh titik tujuan.",
        waypoint: "Klik peta untuk menambah waypoint.",
    };
    statusMessage.value = labelMap[mode] || statusMessage.value;
}

function handleMapClick(event) {
    if (!mapInstance.value) {
        return;
    }

    const { lat, lng } = event.latlng;

    if (activeMode.value === "origin") {
        state.value.originLat = lat;
        state.value.originLng = lng;
        statusMessage.value = "Titik asal diperbarui.";
    } else if (activeMode.value === "destination") {
        state.value.destinationLat = lat;
        state.value.destinationLng = lng;
        statusMessage.value = "Titik tujuan diperbarui.";
    } else {
        state.value.waypoints.push({
            name: `Waypoint ${state.value.waypoints.length + 1}`,
            lat,
            lng,
        });
        statusMessage.value = "Waypoint berhasil ditambahkan.";
    }

    emitChange();
    renderRoute();
}

function removeWaypoint(index) {
    state.value.waypoints.splice(index, 1);
    emitChange();
    renderRoute();
}

function resetWaypoints() {
    state.value.waypoints = [];
    emitChange();
    renderRoute();
}

onMounted(() => {
    if (!mapContainer.value) {
        return;
    }

    mapInstance.value = L.map(mapContainer.value, {
        zoomControl: true,
        attributionControl: false,
    }).setView(defaultCenter, 5);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
    }).addTo(mapInstance.value);

    overlayGroup.value = L.layerGroup().addTo(mapInstance.value);

    mapInstance.value.on("click", handleMapClick);

    renderRoute();
    emitChange();
});
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-wrap items-center gap-3">
            <button
                type="button"
                class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all"
                :class="
                    activeMode === 'origin'
                        ? 'bg-brand-red text-white border-brand-red'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700'
                "
                @click="setMode('origin')"
            >
                Set Asal
            </button>
            <button
                type="button"
                class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all"
                :class="
                    activeMode === 'destination'
                        ? 'bg-brand-red text-white border-brand-red'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700'
                "
                @click="setMode('destination')"
            >
                Set Tujuan
            </button>
            <button
                type="button"
                class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all"
                :class="
                    activeMode === 'waypoint'
                        ? 'bg-brand-red text-white border-brand-red'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700'
                "
                @click="setMode('waypoint')"
            >
                Tambah Waypoint
            </button>
            <button
                type="button"
                class="px-4 py-2 rounded-xl border text-sm font-semibold transition-all bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700"
                @click="resetWaypoints"
            >
                Hapus Waypoint
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
            <button
                type="button"
                class="px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-left hover:border-brand-red/40 transition-all"
                @click="geocodeLocation('origin')"
            >
                <div class="text-xs uppercase tracking-wide text-gray-500">
                    Asal
                </div>
                <div class="font-semibold text-gray-900 dark:text-white">
                    {{ originName || "Belum diisi" }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Ambil koordinat otomatis dari nama asal
                </div>
            </button>

            <button
                type="button"
                class="px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-left hover:border-brand-red/40 transition-all"
                @click="geocodeLocation('destination')"
            >
                <div class="text-xs uppercase tracking-wide text-gray-500">
                    Tujuan
                </div>
                <div class="font-semibold text-gray-900 dark:text-white">
                    {{ destinationName || "Belum diisi" }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Ambil koordinat otomatis dari nama tujuan
                </div>
            </button>
        </div>

        <div
            class="rounded-3xl border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-100 dark:bg-gray-900 min-h-[420px]"
        >
            <div ref="mapContainer" class="w-full h-[420px]"></div>
        </div>

        <div
            class="flex items-start gap-3 rounded-2xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 p-4"
        >
            <i class="fas fa-map-marker-alt text-brand-red mt-0.5"></i>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ statusMessage }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Klik peta untuk menaruh titik sesuai mode aktif, atau drag
                    marker untuk koreksi posisi.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4"
            >
                <div
                    class="text-sm font-bold text-gray-900 dark:text-white mb-2"
                >
                    Koordinat Asal
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ state.originLat ?? "-" }}, {{ state.originLng ?? "-" }}
                </div>
            </div>

            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4"
            >
                <div
                    class="text-sm font-bold text-gray-900 dark:text-white mb-2"
                >
                    Koordinat Tujuan
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ state.destinationLat ?? "-" }},
                    {{ state.destinationLng ?? "-" }}
                </div>
            </div>
        </div>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4"
        >
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div
                        class="text-sm font-bold text-gray-900 dark:text-white"
                    >
                        Waypoint
                    </div>
                    <div class="text-xs text-gray-500">
                        Titik perhentian atau simpul rute tambahan
                    </div>
                </div>
                <button
                    type="button"
                    class="text-xs font-semibold text-brand-red hover:underline"
                    @click="resetWaypoints"
                >
                    Reset
                </button>
            </div>

            <div v-if="state.waypoints.length" class="space-y-3">
                <div
                    v-for="(waypoint, index) in state.waypoints"
                    :key="`${waypoint.name}-${index}`"
                    class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_auto] gap-3 items-center rounded-xl bg-gray-50 dark:bg-gray-900/60 p-3"
                >
                    <input
                        v-model="waypoint.name"
                        type="text"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white"
                        placeholder="Nama waypoint"
                    />
                    <input
                        v-model="waypoint.lat"
                        type="number"
                        step="0.000001"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white"
                        placeholder="Latitude"
                        @change="emitChange"
                    />
                    <input
                        v-model="waypoint.lng"
                        type="number"
                        step="0.000001"
                        class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white"
                        placeholder="Longitude"
                        @change="emitChange"
                    />
                    <button
                        type="button"
                        class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                        @click="removeWaypoint(index)"
                    >
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <div v-else class="text-sm text-gray-500">
                Belum ada waypoint. Gunakan mode tambah waypoint lalu klik peta.
            </div>
        </div>

        <div v-if="loading" class="text-sm text-gray-500">
            Memuat koordinat...
        </div>
    </div>
</template>
