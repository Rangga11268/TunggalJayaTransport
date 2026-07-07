<script setup>
import { onMounted, ref } from "vue";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

const props = defineProps({
    origin: String,
    destination: String,
    originLat: Number,
    originLng: Number,
    destinationLat: Number,
    destinationLng: Number,
    waypoints: Array,
});

const mapContainer = ref(null);

const defaultCenter = [-2.5489, 118.0149];

function normalizeCoordinate(value) {
    if (value === null || value === undefined || value === "") {
        return null;
    }

    const parsed = Number(value);
    return Number.isNaN(parsed) ? null : parsed;
}

function getCoordinates() {
    const originLat = normalizeCoordinate(props.originLat);
    const originLng = normalizeCoordinate(props.originLng);
    const destinationLat = normalizeCoordinate(props.destinationLat);
    const destinationLng = normalizeCoordinate(props.destinationLng);

    const originCoord =
        originLat !== null && originLng !== null
            ? [originLat, originLng]
            : null;

    const destCoord =
        destinationLat !== null && destinationLng !== null
            ? [destinationLat, destinationLng]
            : null;

    // Build waypoint array
    const routeCoordinates = [];

    if (originCoord) {
        routeCoordinates.push(originCoord);
    }

    if (props.waypoints && Array.isArray(props.waypoints)) {
        props.waypoints.forEach((waypoint) => {
            const lat = normalizeCoordinate(waypoint.lat);
            const lng = normalizeCoordinate(waypoint.lng);

            if (lat !== null && lng !== null) {
                routeCoordinates.push([lat, lng]);
            }
        });
    }

    if (destCoord) {
        routeCoordinates.push(destCoord);
    }

    return { originCoord, destCoord, routeCoordinates };
}

const coordinatesData = getCoordinates();
const hasCoordinates = coordinatesData.routeCoordinates.length > 0;

onMounted(() => {
    if (!mapContainer.value) return;

    if (!hasCoordinates) {
        return;
    }

    const { originCoord, destCoord, routeCoordinates } = coordinatesData;

    const map = L.map(mapContainer.value, {
        zoomControl: false,
        attributionControl: false,
    }).setView(originCoord || destCoord || defaultCenter, 7);

    // Dark Mode Tiles (CartoDB Dark Matter)
    L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
        {
            maxZoom: 19,
        },
    ).addTo(map);

    // Origin Marker Icon (Green)
    const originIcon = L.divIcon({
        className: "custom-marker-icon",
        html: `<div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-[0_0_15px_rgba(34,197,94,0.8)]"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    // Waypoint Marker Icon (Yellow/Amber)
    const waypointIcon = L.divIcon({
        className: "custom-marker-icon",
        html: `<div class="w-4 h-4 bg-amber-400 rounded-full border-2 border-white shadow-[0_0_12px_rgba(251,146,60,0.7)]"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    // Destination Marker Icon (Red)
    const destIcon = L.divIcon({
        className: "custom-marker-icon",
        html: `<div class="w-4 h-4 bg-rose-600 rounded-full border-2 border-white shadow-[0_0_15px_rgba(225,29,72,0.8)]"></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });

    // Add Origin Marker
    if (originCoord) {
        L.marker(originCoord, { icon: originIcon })
            .addTo(map)
            .bindPopup(
                `<b style="color: #22c55e;">📍 ${props.origin}</b>
                    <p style="margin: 4px 0; font-size: 12px;">Keberangkatan</p>`,
            )
            .openPopup();
    }

    // Add Waypoint Markers
    if (props.waypoints && Array.isArray(props.waypoints)) {
        props.waypoints.forEach((waypoint, idx) => {
            let waypointCoord;
            let waypointName = waypoint.name || `Perhentian ${idx + 1}`;

            if (waypoint.lat && waypoint.lng) {
                waypointCoord = [waypoint.lat, waypoint.lng];
            } else if (waypoint.name && cityCoords[waypoint.name]) {
                waypointCoord = cityCoords[waypoint.name];
            } else {
                return;
            }

            L.marker(waypointCoord, { icon: waypointIcon }).addTo(map)
                .bindPopup(`<b style="color: #f59e0b;">🛑 ${waypointName}</b>
                            <p style="margin: 4px 0; font-size: 12px;">Perhentian</p>`);
        });
    }

    // Add Destination Marker
    if (destCoord) {
        L.marker(destCoord, { icon: destIcon }).addTo(map)
            .bindPopup(`<b style="color: #e11d48;">📍 ${props.destination}</b>
                    <p style="margin: 4px 0; font-size: 12px;">Tujuan Akhir</p>`);
    }

    // Draw Polyline (Route Line)
    if (routeCoordinates.length > 1) {
        const polyline = L.polyline(routeCoordinates, {
            color: "#e11d48",
            weight: 3,
            opacity: 0.8,
            dashArray: "5, 10",
            lineCap: "round",
            lineJoin: "round",
        }).addTo(map);

        // Fit bounds with padding
        map.fitBounds(polyline.getBounds(), { padding: [60, 60] });
    }
});
</script>

<template>
    <div
        v-if="hasCoordinates"
        ref="mapContainer"
        class="w-full h-full min-h-[300px] z-0"
    ></div>
    <div
        v-else
        class="w-full h-full min-h-[300px] z-0 flex items-center justify-center text-center p-6 bg-gray-100 dark:bg-[#111] text-gray-500 dark:text-gray-400"
    >
        <div>
            <i class="fas fa-map-marked-alt text-3xl mb-3 opacity-40"></i>
            <p>Koordinat rute belum diatur admin.</p>
        </div>
    </div>
</template>

<style>
.custom-div-icon {
    background: none;
    border: none;
}
.leaflet-popup-content-wrapper {
    background: #111 !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px !important;
    font-family: "Manrope", sans-serif !important;
}
.leaflet-popup-tip {
    background: #111 !important;
}
</style>
