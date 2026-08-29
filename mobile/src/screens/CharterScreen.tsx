import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Linking,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import api from "../api/client";
import { ScreenHeader } from "../components/ScreenHeader";
import { SectionHeader } from "../components/SectionHeader";
import { useCustomAlert } from "../context/AlertContext";
import { Compass, MessageCircle } from "lucide-react-native";

// Modular Charter Components
import {
  CharterBusCard,
  CharterBusOption,
  CHARTER_BUS_OPTIONS,
} from "../components/charter/CharterBusCard";
import { CharterBookingForm } from "../components/charter/CharterBookingForm";
import { CharterAmenities } from "../components/charter/CharterAmenities";
import { CharterSummaryBox } from "../components/charter/CharterSummaryBox";

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function CharterScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { showAlert, showWarning } = useCustomAlert();

  const [selectedBus, setSelectedBus] = useState<CharterBusOption>(
    CHARTER_BUS_OPTIONS[0],
  );
  const [pickup, setPickup] = useState("Kuningan / Cirebon");
  const [destination, setDestination] = useState("Yogyakarta / Solo / Bali");
  const [startDate, setStartDate] = useState("2026-09-15");
  const [daysCount, setDaysCount] = useState(2);
  const [busCount, setBusCount] = useState(1);
  const [departureTime, setDepartureTime] = useState("06:00 WIB");
  const [notes, setNotes] = useState("");
  const [loading, setLoading] = useState(false);
  const [focusedField, setFocusedField] = useState<string | null>(null);

  const estimatedTotal = selectedBus.pricePerDay * daysCount * busCount;

  const handleSendWhatsApp = () => {
    if (!pickup.trim() || !destination.trim() || !startDate.trim()) {
      showWarning(
        "Data Belum Lengkap",
        "Harap isi lokasi penjemputan, tujuan wisata, dan tanggal rencana sewa pariwisata.",
      );
      return;
    }

    const message =
      `*FORM RESERVASI SEWA BUS PARIWISATA PO TUNGGAL JAYA*\n\n` +
      `Saya ingin menanyakan reservasi sewa bus pariwisata:\n` +
      `- Kebutuhan bus : ${selectedBus.title} (${selectedBus.seats})\n` +
      `- Jumlah unit : ${busCount} Unit\n` +
      `- Durasi sewa : ${daysCount} Hari\n` +
      `- Titik jemput : ${pickup.trim()}\n` +
      `- Tujuan wisata : ${destination.trim()}\n` +
      `- Tanggal mulai : ${startDate.trim()}\n` +
      `- Jam jemput : ${departureTime.trim()}\n` +
      `- Estimasi biaya : Rp ${estimatedTotal.toLocaleString("id-ID")}\n` +
      (notes.trim() ? `- Catatan : ${notes.trim()}\n` : "") +
      `\nMohon info ketersediaan armada dan penawaran terbaiknya. Terima kasih.`;

    const url = `https://wa.me/6281122222353?text=${encodeURIComponent(
      message,
    )}`;
    Linking.openURL(url).catch(() => {
      showWarning(
        "Tidak Bisa Membuka WhatsApp",
        "Pastikan aplikasi WhatsApp telah terpasang di perangkat Anda.",
      );
    });
  };

  const handleSubmit = async () => {
    if (!pickup.trim() || !destination.trim() || !startDate.trim()) {
      showWarning(
        "Form Belum Lengkap",
        "Silakan lengkapi titik jemput, kota tujuan, dan tanggal sewa.",
      );
      return;
    }

    try {
      setLoading(true);
      await api
        .post("/charters", {
          bus_type: selectedBus.title,
          bus_requests: [
            {
              type: selectedBus.title,
              count: busCount,
              seat_configuration: selectedBus.seats,
            },
          ],
          pickup_location: pickup.trim(),
          destination: destination.trim(),
          pickup_date: startDate.trim(),
          return_date: startDate.trim(),
          days_count: daysCount,
          bus_count: busCount,
          total_price: estimatedTotal,
          notes: notes.trim(),
        })
        .catch(() => {});

      showAlert({
        title: "Pengajuan Terkirim",
        message:
          "Permintaan sewa bus pariwisata Anda telah tercatat. Hubungi WhatsApp CS kami untuk konfirmasi ketersediaan instan.",
        type: "success",
        buttons: [
          {
            text: "Lihat Riwayat",
            style: "cancel",
            onPress: () =>
              navigation.navigate("MainTabs", {
                screen: "BookingHistory",
              } as any),
          },
          {
            text: "Buka WhatsApp",
            style: "default",
            onPress: handleSendWhatsApp,
          },
        ],
      });
    } catch (e: any) {
      console.log("Error submitting charter request:", e);
      handleSendWhatsApp();
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Sewa Bus Pariwisata"
        subtitle="PO Tunggal Jaya Transport"
        showBack={true}
        onBack={() => navigation.goBack()}
        rightElement={
          <TouchableOpacity
            activeOpacity={0.7}
            onPress={handleSendWhatsApp}
            style={styles.waHeaderBtn}
          >
            <MessageCircle size={18} color="#059669" />
          </TouchableOpacity>
        }
      />

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
      >
        {/* Banner Hero Card */}
        <LinearGradient
          colors={["#2563EB", "#1D4ED8"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={styles.heroBanner}
        >
          <View style={styles.heroBadge}>
            <Compass size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
            <Text style={styles.heroBadgeText}>
              TUNGGAL JAYA TOUR &amp; TRAVEL
            </Text>
          </View>
          <Text style={styles.heroHeading}>Carter Bus Pariwisata Resmi</Text>
          <Text style={styles.heroSubtitle}>
            Pilihan armada big bus berkapasitas 50 s.d 59 kursi, suspensi udara
            empuk, dan kru ramah berlisensi resmi.
          </Text>
        </LinearGradient>

        {/* 1. Kategori / Tipe Bus Pariwisata Resmi */}
        <SectionHeader
          title="Pilih Kategori Bus"
          subtitle="Sesuai Kapasitas Rombongan"
          style={{ marginBottom: 12 }}
        />

        <View style={styles.busOptionsList}>
          {CHARTER_BUS_OPTIONS.map((bus) => (
            <CharterBusCard
              key={bus.id}
              bus={bus}
              isSelected={selectedBus.id === bus.id}
              onSelect={() => setSelectedBus(bus)}
            />
          ))}
        </View>

        {/* 2. Formulir Rencana Perjalanan */}
        <CharterBookingForm
          pickup={pickup}
          destination={destination}
          startDate={startDate}
          departureTime={departureTime}
          daysCount={daysCount}
          busCount={busCount}
          notes={notes}
          focusedField={focusedField}
          onSetPickup={setPickup}
          onSetDestination={setDestination}
          onSetStartDate={setStartDate}
          onSetDepartureTime={setDepartureTime}
          onSetDaysCount={setDaysCount}
          onSetBusCount={setBusCount}
          onSetNotes={setNotes}
          onSetFocusedField={setFocusedField}
        />

        {/* 3. Fasilitas & Destinasi Wisata */}
        <CharterAmenities />

        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Booking Action Bar */}
      <CharterSummaryBox
        daysCount={daysCount}
        busCount={busCount}
        estimatedTotal={estimatedTotal}
        loading={loading}
        onSubmit={handleSubmit}
        onSendWhatsApp={handleSendWhatsApp}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  waHeaderBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "rgba(5, 150, 105, 0.1)",
    justifyContent: "center",
    alignItems: "center",
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
  },
  heroBanner: {
    borderRadius: 22,
    padding: 20,
    marginBottom: 20,
  },
  heroBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    alignSelf: "flex-start",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 20,
    marginBottom: 10,
  },
  heroBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 10,
    color: "#FFFFFF",
    letterSpacing: 0.5,
  },
  heroHeading: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: "#FFFFFF",
    marginBottom: 6,
  },
  heroSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#E2E8F0",
    lineHeight: 18,
  },
  busOptionsList: {
    marginBottom: 20,
  },
});
