<script setup>
import { onMounted, ref } from "vue";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

const props = defineProps({
    origin: String,
    destination: String,
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
};

onMounted(() => {
    if (!mapContainer.value) return;

    const originCoord = cityCoords[props.origin] || [-6.2088, 106.8456];
    const destCoord = cityCoords[props.destination] || [-7.2575, 112.7521];

    const map = L.map(mapContainer.value, {
        zoomControl: false,
        attributionControl: false,
    }).setView(originCoord, 7);

    // Dark Mode Tiles (CartoDB Dark Matter)
    L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
        {
            maxZoom: 19,
        }
    ).addTo(map);

    // Custom Icon
    const dotIcon = L.divIcon({
        className: "custom-div-icon",
        html: `<div class="w-3 h-3 bg-rose-600 rounded-full border-2 border-white shadow-[0_0_10px_rgba(225,29,72,0.8)]"></div>`,
        iconSize: [12, 12],
        iconAnchor: [6, 6],
    });

    // Markers
    L.marker(originCoord, { icon: dotIcon })
        .addTo(map)
        .bindPopup(`<b>Origin:</b> ${props.origin}`)
        .openPopup();
    L.marker(destCoord, { icon: dotIcon })
        .addTo(map)
        .bindPopup(`<b>Destination:</b> ${props.destination}`);

    // Polyline (Route Line)
    const polyline = L.polyline([originCoord, destCoord], {
        color: "#e11d48",
        weight: 3,
        opacity: 0.8,
        dashArray: "10, 10",
        lineCap: "round",
    }).addTo(map);

    // Fit bounds
    map.fitBounds(polyline.getBounds(), { padding: [50, 50] });

    // Animation Effect (subtle pulse on markers could be added via CSS)
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
