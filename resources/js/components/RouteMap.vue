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

// Mock coordinates for Indonesian cities
const cityCoords = {
    Jakarta: [-6.2088, 106.8456],
    Bandung: [-6.9175, 107.6191],
    Surabaya: [-7.2575, 112.7521],
    Semarang: [-7.0051, 110.4381],
    Yogyakarta: [-7.7956, 110.3695],
    Solo: [-7.5755, 110.8243],
    Malang: [-7.9666, 112.6326],
    Cirebon: [-6.732, 108.5523],
    Tegal: [-6.8677, 109.1378],
    Pekalongan: [-6.8886, 109.6753],
    Purwokerto: [-7.4244, 109.2303],
    Magelang: [-7.4706, 110.2178],
    Salatiga: [-7.3305, 110.5084],
    Kediri: [-7.848, 112.0178],
    Madiun: [-7.6298, 111.5239],
    Blitar: [-8.0954, 112.1623],
    Probolinggo: [-7.7569, 113.2161],
    Jember: [-8.1724, 113.6995],
    Banyuwangi: [-8.2192, 114.3691],
    Kuningan: [-6.9788, 108.4846],
    Cipali: [-6.6833, 108.4167],
    Deresan: [-6.2274, 106.8231],
    Rangkasbitung: [-6.3667, 106.2167],
    Banten: [-6.1667, 106.1667],
};

function getCoordinates() {
    const originCoord =
        props.originLat && props.originLng
            ? [props.originLat, props.originLng]
            : cityCoords[props.origin] || [-6.2088, 106.8456];

    const destCoord =
        props.destinationLat && props.destinationLng
            ? [props.destinationLat, props.destinationLng]
            : cityCoords[props.destination] || [-7.2575, 112.7521];

    // Build waypoint array
    let routeCoordinates = [originCoord];

    if (props.waypoints && Array.isArray(props.waypoints)) {
        props.waypoints.forEach((waypoint) => {
            if (waypoint.lat && waypoint.lng) {
                routeCoordinates.push([waypoint.lat, waypoint.lng]);
            } else if (waypoint.name && cityCoords[waypoint.name]) {
                routeCoordinates.push(cityCoords[waypoint.name]);
            }
        });
    }

    routeCoordinates.push(destCoord);

    return { originCoord, destCoord, routeCoordinates };
}

onMounted(() => {
    if (!mapContainer.value) return;

    const { originCoord, destCoord, routeCoordinates } = getCoordinates();

    const map = L.map(mapContainer.value, {
        zoomControl: false,
        attributionControl: false,
    }).setView(originCoord, 7);

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
    L.marker(originCoord, { icon: originIcon })
        .addTo(map)
        .bindPopup(
            `<b style="color: #22c55e;">📍 ${props.origin}</b>
                    <p style="margin: 4px 0; font-size: 12px;">Keberangkatan</p>`,
        )
        .openPopup();

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
    L.marker(destCoord, { icon: destIcon }).addTo(map)
        .bindPopup(`<b style="color: #e11d48;">📍 ${props.destination}</b>
                    <p style="margin: 4px 0; font-size: 12px;">Tujuan Akhir</p>`);

    // Draw Polyline (Route Line)
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
});
</script>

<template>
    <div ref="mapContainer" class="w-full h-full min-h-[300px] z-0"></div>
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
