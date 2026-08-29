import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  TextInput,
  Dimensions,
  Platform,
  Alert,
  ActivityIndicator,
  Linking,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import api from "../api/client";
import { formatIndonesianDate } from "../utils/format";
import { ScreenHeader } from "../components/ScreenHeader";
import { SectionHeader } from "../components/SectionHeader";
import { useCustomAlert } from "../context/AlertContext";
import {
  ArrowLeft,
  MapPin,
  Calendar,
  Clock,
  CheckCircle2,
  Users,
  ShieldCheck,
  Compass,
  MessageCircle,
  Plus,
  Minus,
  Sparkles,
  Info,
  Tv,
  Wifi,
  Coffee,
  Check,
} from "lucide-react-native";

const { width } = Dimensions.get("window");

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export interface CharterBusOption {
  id: string;
  title: string;
  seats: string;
  badge: string;
  badgeColor: string;
  desc: string;
  pricePerDay: number;
  image: any;
  features: string[];
}

// Official Charter Bus Types from Tunggal Jaya Web Profile
export const CHARTER_BUS_OPTIONS: CharterBusOption[] = [
  {
    id: "bigbus-50",
    title: "Bigbus Executive",
    seats: "50 Seat (2-2)",
    badge: "Armada Viral (Kylo Ren)",
    badgeColor: "#2563EB",
    desc: "Unit ikonik Kylo Ren, Bentas & Kids Panda dengan suspensi udara empuk.",
    pricePerDay: 3800000,
    image: require("../../assets/images/kylorenParwis.webp"),
    features: [
      "Air Suspension Empuk",
      "Audio Karaoke & Disco Light",
      "Smart Android TV",
      "USB Fast Charger",
      "Dispenser & Bagasi Luas",
    ],
  },
  {
    id: "bigbus-59",
    title: "Bigbus Max Capacity",
    seats: "59 Seat (2-3)",
    badge: "Paling Hemat (Study Tour)",
    badgeColor: "#059669",
    desc: "Kapasitas muatan terbesar untuk rombongan study tour sekolah & instansi.",
    pricePerDay: 3500000,
    image: require("../../assets/images/resiBisma.webp"),
    features: [
      "Full AC Dingin Merata",
      "Reclining Seat 2-3 Nyaman",
      "Audio Music System",
      "Bagasi Ekstra Kapasitas",
      "Kru Berpengalaman",
    ],
  },
  {
    id: "bigbus-custom",
    title: "Bigbus Seat Custom / Legrest",
    seats: "Seat Custom (Legrest 40-45)",
    badge: "Eksklusif VIP Long Trip",
    badgeColor: "#D97706",
    desc: "Konfigurasi kursi santai fleksibel & sandaran kaki legrest untuk kenyamanan maksimal.",
    pricePerDay: 4200000,
    image: require("../../assets/images/primadona.webp"),
    features: [
      "Pneumatic Air Suspension",
      "Legrest Seat Santai",
      "Audio Karaoke System",
      "Smart TV & USB Port",
      "Toilet Bersih Terawat",
    ],
  },
];

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
      `- Jemputan : ${pickup.trim()}\n` +
      `- Tujuan : ${destination.trim()}\n` +
      `- Tgl & bulan berangkat : ${formatIndonesianDate(startDate, false)}\n` +
      `- Berangkat jam berapa : ${departureTime}\n` +
      `- Berapa hari : ${daysCount} Hari\n` +
      `- Estimasi Biaya : Rp ${estimatedTotal.toLocaleString("id-ID")}\n` +
      `- Keterangan : ${notes.trim() || "Konsultasi Sewa Bus Pariwisata via Aplikasi Mobile"}\n\n` +
      `Mohon info ketersediaan dan penawarannya. Terima kasih.`;

    const targetPhone = "6281122222353";
    const encoded = encodeURIComponent(message);
    Linking.openURL(
      `https://api.whatsapp.com/send?phone=${targetPhone}&text=${encoded}`,
    );
  };

  const handleSubmit = async () => {
    if (!pickup.trim() || !destination.trim() || !startDate.trim()) {
      showWarning(
        "Data Belum Lengkap",
        "Harap isi lokasi penjemputan, tujuan wisata, dan tanggal rencana sewa pariwisata.",
      );
      return;
    }

    try {
      setLoading(true);
      await api
        .post("/charter/request", {
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
          {CHARTER_BUS_OPTIONS.map((bus) => {
            const isSelected = selectedBus.id === bus.id;
            return (
              <TouchableOpacity
                key={bus.id}
                activeOpacity={0.88}
                onPress={() => setSelectedBus(bus)}
                style={[styles.busCard, isSelected && styles.busCardSelected]}
              >
                <Image
                  source={bus.image}
                  style={styles.busCardImage}
                  resizeMode="cover"
                />

                <View style={styles.busCardBody}>
                  <View style={styles.busCardHeader}>
                    <View style={{ flex: 1 }}>
                      <Text style={styles.busCardTitle}>{bus.title}</Text>
                      <Text style={styles.busCardSeats}>{bus.seats}</Text>
                    </View>
                    <View
                      style={[
                        styles.categoryBadge,
                        { backgroundColor: `${bus.badgeColor}15` },
                      ]}
                    >
                      <Text
                        style={[
                          styles.categoryBadgeText,
                          { color: bus.badgeColor },
                        ]}
                      >
                        {bus.badge}
                      </Text>
                    </View>
                  </View>

                  <Text style={styles.busCardDesc}>{bus.desc}</Text>

                  {/* Feature Chips */}
                  <View style={styles.featuresRow}>
                    {bus.features.slice(0, 3).map((feat, idx) => (
                      <View key={idx} style={styles.featurePill}>
                        <Check
                          size={11}
                          color={COLORS.brandBlue}
                          style={{ marginRight: 4 }}
                        />
                        <Text style={styles.featurePillText}>{feat}</Text>
                      </View>
                    ))}
                  </View>

                  <View style={styles.busCardFooter}>
                    <View>
                      <Text style={styles.priceLabel}>
                        Estimasi Sewa / Hari
                      </Text>
                      <Text style={styles.priceValue}>
                        Rp {bus.pricePerDay.toLocaleString("id-ID")}
                      </Text>
                    </View>

                    <View
                      style={[
                        styles.selectRadio,
                        isSelected && styles.selectRadioActive,
                      ]}
                    >
                      {isSelected && <View style={styles.selectRadioDot} />}
                    </View>
                  </View>
                </View>
              </TouchableOpacity>
            );
          })}
        </View>

        {/* 2. Formulir Rencana Perjalanan */}
        <View style={styles.formCard}>
          <Text style={styles.formCardTitle}>Rencana Perjalanan Wisata</Text>

          {/* Pickup Location */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>LOKASI PENJEMPUTAN</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "pickup" && styles.inputContainerFocused,
              ]}
            >
              <MapPin
                size={18}
                color={focusedField === "pickup" ? COLORS.brandBlue : "#6B7280"}
              />
              <TextInput
                style={styles.textInput}
                value={pickup}
                onChangeText={setPickup}
                placeholder="Contoh: Pool Cirendang Kuningan / Cirebon / Jakarta"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField("pickup")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Destination */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>KOTA TUJUAN WISATA</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "destination" && styles.inputContainerFocused,
              ]}
            >
              <MapPin
                size={18}
                color={
                  focusedField === "destination" ? COLORS.brandBlue : "#6B7280"
                }
              />
              <TextInput
                style={styles.textInput}
                value={destination}
                onChangeText={setDestination}
                placeholder="Contoh: Yogyakarta / Bandung / Bali / Malang / Pangandaran"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField("destination")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Start Date */}
          <View style={styles.inputGroup}>
            <View
              style={{
                flexDirection: "row",
                justifyContent: "space-between",
                alignItems: "center",
                marginBottom: 8,
              }}
            >
              <Text style={styles.inputLabel}>TANGGAL KEBERANGKATAN</Text>
              <Text
                style={{
                  fontFamily: "PlusJakartaSans_700Bold",
                  fontSize: 11,
                  color: COLORS.brandBlue,
                }}
              >
                {formatIndonesianDate(startDate, false)}
              </Text>
            </View>
            <View
              style={[
                styles.inputContainer,
                focusedField === "startDate" && styles.inputContainerFocused,
              ]}
            >
              <Calendar
                size={18}
                color={
                  focusedField === "startDate" ? COLORS.brandBlue : "#6B7280"
                }
              />
              <TextInput
                style={styles.textInput}
                value={startDate}
                onChangeText={setStartDate}
                placeholder="YYYY-MM-DD (Contoh: 2026-09-15)"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField("startDate")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Departure Time */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>JAM KEBERANGKATAN PENJEMPUTAN</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "time" && styles.inputContainerFocused,
              ]}
            >
              <Clock
                size={18}
                color={focusedField === "time" ? COLORS.brandBlue : "#6B7280"}
              />
              <TextInput
                style={styles.textInput}
                value={departureTime}
                onChangeText={setDepartureTime}
                placeholder="Contoh: 06:00 WIB"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField("time")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Steppers: Duration & Bus Count */}
          <View style={styles.countersRow}>
            {/* Days Count */}
            <View style={styles.counterBox}>
              <Text style={styles.counterLabel}>DURASI (HARI)</Text>
              <View style={styles.stepperContainer}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setDaysCount(Math.max(1, daysCount - 1))}
                  style={styles.stepBtn}
                >
                  <Minus size={16} color="#111827" />
                </TouchableOpacity>
                <Text style={styles.stepValueText}>{daysCount} Hari</Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setDaysCount(daysCount + 1)}
                  style={styles.stepBtn}
                >
                  <Plus size={16} color="#111827" />
                </TouchableOpacity>
              </View>
            </View>

            {/* Bus Count */}
            <View style={styles.counterBox}>
              <Text style={styles.counterLabel}>JUMLAH UNIT</Text>
              <View style={styles.stepperContainer}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setBusCount(Math.max(1, busCount - 1))}
                  style={styles.stepBtn}
                >
                  <Minus size={16} color="#111827" />
                </TouchableOpacity>
                <Text style={styles.stepValueText}>{busCount} Unit</Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setBusCount(busCount + 1)}
                  style={styles.stepBtn}
                >
                  <Plus size={16} color="#111827" />
                </TouchableOpacity>
              </View>
            </View>
          </View>

          {/* Notes */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>
              CATATAN KHUSUS / TUJUAN TAMBAHAN
            </Text>
            <View
              style={[
                styles.textAreaContainer,
                focusedField === "notes" && styles.inputContainerFocused,
              ]}
            >
              <TextInput
                style={styles.textAreaInput}
                value={notes}
                onChangeText={setNotes}
                placeholder="Rencana rute singgah, daftar destinasi wisata, atau permintaan khusus..."
                placeholderTextColor="#9CA3AF"
                multiline
                numberOfLines={3}
                onFocus={() => setFocusedField("notes")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>
        </View>

        {/* Official Facilities Card */}
        <View style={styles.amenitiesCard}>
          <Text style={styles.amenitiesTitle}>
            Standar Fasilitas Pariwisata PO Tunggal Jaya
          </Text>
          <View style={styles.amenitiesGrid}>
            <View style={styles.amenityItem}>
              <ShieldCheck size={18} color={COLORS.brandBlue} />
              <Text style={styles.amenityText}>Kru Driver Berlisensi</Text>
            </View>
            <View style={styles.amenityItem}>
              <Tv size={18} color={COLORS.brandBlue} />
              <Text style={styles.amenityText}>Full Audio &amp; Karaoke</Text>
            </View>
            <View style={styles.amenityItem}>
              <Wifi size={18} color={COLORS.brandBlue} />
              <Text style={styles.amenityText}>Air Suspension Nyaman</Text>
            </View>
            <View style={styles.amenityItem}>
              <Coffee size={18} color={COLORS.brandBlue} />
              <Text style={styles.amenityText}>Dispenser &amp; Cooler</Text>
            </View>
          </View>
        </View>

        {/* Destinasi Wisata Populer */}
        <View style={styles.sectionBox}>
          <SectionHeader
            title="Destinasi Wisata Favorit"
            subtitle="Rute Populer Rombongan Pariwisata"
            style={{ marginBottom: 12 }}
          />

          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.destScroll}
          >
            <View style={styles.destCard}>
              <Text style={styles.destCity}>YOGYAKARTA</Text>
              <Text style={styles.destTitle}>
                Malioboro &amp; Candi Borobudur
              </Text>
              <Text style={styles.destSub}>
                Paket 3H2M • Free Tol &amp; Parkir Wisata
              </Text>
            </View>

            <View style={styles.destCard}>
              <Text style={[styles.destCity, { color: "#059669" }]}>
                BANDUNG
              </Text>
              <Text style={styles.destTitle}>Lembang &amp; Ciwidey Tour</Text>
              <Text style={styles.destSub}>
                Paket 2H1M • Rute Fleksibel Acara
              </Text>
            </View>

            <View style={styles.destCard}>
              <Text style={[styles.destCity, { color: "#D97706" }]}>
                PANGANDARAN
              </Text>
              <Text style={styles.destTitle}>Pantai &amp; Green Canyon</Text>
              <Text style={styles.destSub}>Paket 2H1M • Rombongan Kompak</Text>
            </View>
          </ScrollView>
        </View>

        {/* Fasilitas Termasuk Sewa */}
        <View style={styles.sectionBox}>
          <SectionHeader
            title="Fasilitas Termasuk Sewa"
            subtitle="Jaminan Layanan Standar Pariwisata PO Tunggal Jaya"
            style={{ marginBottom: 12 }}
          />
          <View style={styles.inclusiveGrid}>
            <View style={styles.inclusiveItem}>
              <Check size={16} color="#059669" />
              <Text style={styles.inclusiveText}>
                BBM Bus &amp; Armada Standar Pariwisata
              </Text>
            </View>
            <View style={styles.inclusiveItem}>
              <Check size={16} color="#059669" />
              <Text style={styles.inclusiveText}>
                Kru Driver &amp; Co-Driver Berlisensi Resmi
              </Text>
            </View>
            <View style={styles.inclusiveItem}>
              <Check size={16} color="#059669" />
              <Text style={styles.inclusiveText}>
                Audio Karaoke, TV LED &amp; Mic Wireless
              </Text>
            </View>
            <View style={styles.inclusiveItem}>
              <Check size={16} color="#059669" />
              <Text style={styles.inclusiveText}>
                Asuransi Jasa Raharja Resmi
              </Text>
            </View>
          </View>
        </View>

        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Booking Action Bar */}
      <View style={styles.bottomBar}>
        <View style={styles.bottomPriceCol}>
          <Text style={styles.bottomPriceLabel}>
            Estimasi ({daysCount} Hari x {busCount} Unit)
          </Text>
          <Text style={styles.bottomPriceValue}>
            Rp {estimatedTotal.toLocaleString("id-ID")}
          </Text>
        </View>

        <View style={styles.bottomActionRow}>
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={handleSubmit}
            disabled={loading}
            style={styles.bookBtn}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.bookBtnText}>Ajukan Sewa</Text>
            )}
          </TouchableOpacity>

          <TouchableOpacity
            activeOpacity={0.85}
            onPress={handleSendWhatsApp}
            style={styles.waQuickBtn}
          >
            <MessageCircle size={18} color="#FFFFFF" />
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  topBar: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 12,
    backgroundColor: "#FFFFFF",
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "#F1F4F8",
    justifyContent: "center",
    alignItems: "center",
  },
  topBarTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
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
  },
  sectionBox: {
    marginBottom: 20,
  },
  heroBanner: {
    borderRadius: 22,
    padding: 20,
    marginBottom: 20,
  },
  heroBadge: {
    flexDirection: "row",
    alignItems: "center",
    alignSelf: "flex-start",
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
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
    fontSize: 20,
    color: "#FFFFFF",
    marginBottom: 6,
    letterSpacing: -0.4,
  },
  heroSubtitle: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "rgba(255, 255, 255, 0.9)",
    lineHeight: 18,
  },
  sectionHeaderBox: {
    marginBottom: 12,
  },
  sectionHeading: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#111827",
  },
  sectionSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#6B7280",
    marginTop: 2,
  },
  busOptionsList: {
    gap: 14,
    marginBottom: 24,
  },
  busCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    borderWidth: 1.5,
    borderColor: "#E2E8F0",
    overflow: "hidden",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  busCardSelected: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#F8FAFC",
  },
  busCardImage: {
    width: "100%",
    height: 140,
  },
  busCardBody: {
    padding: 16,
  },
  busCardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-start",
    marginBottom: 6,
  },
  busCardTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#111827",
    marginBottom: 2,
  },
  busCardSeats: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  categoryBadge: {
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
  },
  categoryBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
  },
  busCardDesc: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#4B5563",
    lineHeight: 18,
    marginBottom: 10,
  },
  featuresRow: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 6,
    marginBottom: 14,
  },
  featurePill: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
  },
  featurePillText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10.5,
    color: "#1E40AF",
  },
  busCardFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: "#F1F4F8",
  },
  priceLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#6B7280",
  },
  priceValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
  },
  selectRadio: {
    width: 22,
    height: 22,
    borderRadius: 11,
    borderWidth: 2,
    borderColor: "#CBD5E1",
    justifyContent: "center",
    alignItems: "center",
  },
  selectRadioActive: {
    borderColor: COLORS.brandBlue,
  },
  selectRadioDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: COLORS.brandBlue,
  },
  formCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    padding: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 20,
  },
  formCardTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
    marginBottom: 16,
  },
  inputGroup: {
    marginBottom: 16,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#374151",
    marginBottom: 8,
    letterSpacing: 0.5,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    borderWidth: 1.2,
    borderColor: "#E2E8F0",
    paddingHorizontal: 14,
    height: 48,
    gap: 10,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#FFFFFF",
  },
  textInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#111827",
    paddingVertical: 0,
    ...(Platform.OS === "web" ? ({ outlineStyle: "none" } as any) : {}),
  },
  countersRow: {
    flexDirection: "row",
    gap: 12,
    marginBottom: 16,
  },
  counterBox: {
    flex: 1,
  },
  counterLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#374151",
    marginBottom: 8,
    letterSpacing: 0.5,
  },
  stepperContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    borderWidth: 1.2,
    borderColor: "#E2E8F0",
    paddingHorizontal: 6,
    height: 48,
  },
  stepBtn: {
    width: 34,
    height: 34,
    borderRadius: 10,
    backgroundColor: "#FFFFFF",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  stepValueText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  textAreaContainer: {
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    borderWidth: 1.2,
    borderColor: "#E2E8F0",
    paddingHorizontal: 14,
    paddingVertical: 10,
    minHeight: 70,
  },
  textAreaInput: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#111827",
    textAlignVertical: "top",
    ...(Platform.OS === "web" ? ({ outlineStyle: "none" } as any) : {}),
  },
  amenitiesCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 16,
  },
  amenitiesTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
    marginBottom: 14,
  },
  amenitiesGrid: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 10,
  },
  amenityItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    width: "47%",
  },
  amenityText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#4B5563",
  },
  destScroll: {
    flexDirection: "row",
    gap: 12,
    paddingBottom: 4,
  },
  destCard: {
    width: 200,
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  destCity: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: COLORS.brandBlue,
    letterSpacing: 0.5,
    marginBottom: 4,
  },
  destTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#0F172A",
    marginBottom: 3,
  },
  destSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#64748B",
  },
  inclusiveGrid: {
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    gap: 10,
  },
  inclusiveItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  inclusiveText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: "#334155",
  },
  bottomBar: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: "#FFFFFF",
    borderTopWidth: 1,
    borderTopColor: "#E2E8F0",
    paddingHorizontal: 20,
    paddingTop: 12,
    paddingBottom: Platform.OS === "ios" ? 28 : 16,
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: -4 },
        shadowOpacity: 0.08,
        shadowRadius: 12,
      },
      android: {
        elevation: 12,
      },
      web: {
        boxShadow: "0px -4px 20px rgba(15, 23, 42, 0.08)",
      },
    }),
  },
  bottomPriceCol: {
    flex: 1,
  },
  bottomPriceLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
  },
  bottomPriceValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: COLORS.brandBlue,
  },
  bottomActionRow: {
    flexDirection: "row",
    gap: 8,
  },
  bookBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 20,
    height: 46,
    borderRadius: 23,
    justifyContent: "center",
    alignItems: "center",
  },
  bookBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
  waQuickBtn: {
    width: 46,
    height: 46,
    borderRadius: 23,
    backgroundColor: "#059669",
    justifyContent: "center",
    alignItems: "center",
  },
});
