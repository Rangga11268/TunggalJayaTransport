import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  Platform,
  ActivityIndicator,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation, useRoute } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import {
  ArrowLeft,
  Star,
  User,
  Clock,
  ShieldCheck,
  CheckCircle,
  Tag,
  FileText,
  MessageSquare,
  MapPin,
  Wifi,
  Tv,
  Coffee,
  Armchair,
  Sparkles,
  Share2,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import apiClient from "../api/client";
import { formatIndonesianTime } from "../utils/format";

const { width } = Dimensions.get("window");

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function ScheduleDetailScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<any>();
  const { scheduleId, date } = route.params || { scheduleId: 1 };

  const [activeTab, setActiveTab] = useState<"deals" | "details" | "reviews">(
    "details",
  );
  const [schedule, setSchedule] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchDetail();
  }, [scheduleId, date]);

  const fetchDetail = async () => {
    try {
      setLoading(true);
      const res = await apiClient
        .get(`/schedules/${scheduleId}`, { params: { date } })
        .catch(() => apiClient.get("/schedules"));

      const data = res.data?.data;
      if (data && !Array.isArray(data)) {
        setSchedule(data);
      } else {
        const list = Array.isArray(res.data) ? res.data : res.data?.data || [];
        const found =
          list.find((s: any) => s.id === Number(scheduleId)) || list[0];
        setSchedule(found);
      }
    } catch (e) {
      console.log("Error loading schedule detail:", e);
    } finally {
      setLoading(false);
    }
  };

  const tabs = [
    { id: "details", label: "Spesifikasi", icon: FileText },
    { id: "deals", label: "Promo & Tarif", icon: Tag },
    { id: "reviews", label: "Ulasan (4.8)", icon: MessageSquare },
  ];

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={COLORS.brandRed} />
      </View>
    );
  }

  const busName = schedule?.bus?.name || "Resi Bisma";
  const busType =
    schedule?.bus?.bus_type || schedule?.bus?.type || "Executive";
  const plateNumber = schedule?.bus?.plate_number || "E 7777 TJ";
  const capacity = schedule?.bus?.capacity || 50;
  const origin = schedule?.route?.origin || "Jakarta";
  const destination = schedule?.route?.destination || "Kuningan";
  const price = Number(schedule?.price || 180000).toLocaleString("id-ID");
  const depTime = formatIndonesianTime(schedule?.departure_time, "07:00");
  const arrTime = formatIndonesianTime(schedule?.arrival_time, "13:00");

  const getImageSource = () => {
    const name = (busName || "").toLowerCase();
    if (name.includes("primadona"))
      return require("../../assets/images/primadona.webp");
    if (name.includes("bentas"))
      return require("../../assets/images/bentas01.webp");
    if (name.includes("kyloren"))
      return require("../../assets/images/kylorenParwis.webp");
    return require("../../assets/images/resiBisma.webp");
  };

  return (
    <View style={styles.container}>
      {/* Full-width Top Hero Photo */}
      <View style={styles.heroContainer}>
        <Image
          source={getImageSource()}
          style={styles.heroImage}
          resizeMode="cover"
        />
        <LinearGradient
          colors={[
            "rgba(17, 24, 39, 0.4)",
            "transparent",
            "rgba(17, 24, 39, 0.9)",
          ]}
          locations={[0, 0.35, 1]}
          style={styles.heroGradient}
        >
          {/* Header Navigation Bar */}
          <SafeAreaView edges={["top"]} style={styles.navBar}>
            <TouchableOpacity
              activeOpacity={0.7}
              onPress={() => navigation.goBack()}
              style={styles.navIconBtn}
            >
              <ArrowLeft size={18} color="#FFFFFF" />
            </TouchableOpacity>

            <Text style={styles.navTitle}>Detail Unit &amp; Rute</Text>

            <TouchableOpacity activeOpacity={0.7} style={styles.navIconBtn}>
              <Share2 size={18} color="#FFFFFF" />
            </TouchableOpacity>
          </SafeAreaView>

          {/* Floating Route Info Overlay */}
          <View style={styles.routeHeaderOverlay}>
            <View style={styles.routeHeaderLeft}>
              <View style={styles.badgeLine}>
                <Text style={styles.badgeLineText}>{busType}</Text>
              </View>
              <Text style={styles.routeHeaderTitle}>{busName}</Text>
              <Text style={styles.routeHeaderSub}>
                {origin} ↔ {destination}
              </Text>
            </View>

            <View style={styles.routeHeaderRight}>
              <View style={styles.starsRow}>
                <Star size={13} color="#FFB800" fill="#FFB800" />
                <Text style={styles.starsText}>4.8 (9.6k)</Text>
              </View>
              <Text style={styles.routeHeaderPrice}>
                Rp {price}{" "}
                <Text style={styles.routeHeaderPriceSub}>/ Seat</Text>
              </Text>
            </View>
          </View>
        </LinearGradient>
      </View>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* Tab Switcher Pills */}
        <View style={styles.tabsRow}>
          {tabs.map((t) => {
            const isActive = activeTab === t.id;
            const IconComp = t.icon;
            return (
              <TouchableOpacity
                key={t.id}
                activeOpacity={0.8}
                onPress={() => setActiveTab(t.id as any)}
                style={[
                  styles.tabPill,
                  isActive ? styles.tabPillActive : styles.tabPillInactive,
                ]}
              >
                <IconComp size={15} color={isActive ? "#FFFFFF" : "#4B5563"} />
                <Text
                  style={[
                    styles.tabPillText,
                    isActive
                      ? styles.tabPillTextActive
                      : styles.tabPillTextInactive,
                  ]}
                >
                  {t.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </View>

        {/* Tab 1: Spesifikasi Detail Unit */}
        {activeTab === "details" && (
          <View style={styles.sectionContainer}>
            {/* Unit Specs Card */}
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Spesifikasi Unit Bus</Text>
              <View style={styles.specsGrid}>
                <View style={styles.specBox}>
                  <Text style={styles.specLabel}>Kelas</Text>
                  <Text style={styles.specValue}>{busType}</Text>
                </View>
                <View style={styles.specBox}>
                  <Text style={styles.specLabel}>Kapasitas</Text>
                  <Text style={styles.specValue}>{capacity} Kursi</Text>
                </View>
                <View style={styles.specBox}>
                  <Text style={styles.specLabel}>Plat Nomor</Text>
                  <Text style={styles.specValue}>{plateNumber}</Text>
                </View>
                <View style={styles.specBox}>
                  <Text style={styles.specLabel}>Konfigurasi</Text>
                  <Text style={styles.specValue}>2 - 2 Reclining</Text>
                </View>
              </View>
            </View>

            {/* Route Timeline Card */}
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Jadwal &amp; Rute Perjalanan</Text>
              <View style={styles.timelineWrapper}>
                <View style={styles.timelineItem}>
                  <View style={styles.timelineDotActive} />
                  <View style={styles.timelineInfo}>
                    <Text style={styles.timelineTime}>{depTime} WIB</Text>
                    <Text style={styles.timelinePlace}>
                      Pool Keberangkatan {origin}
                    </Text>
                    <Text style={styles.timelineSub}>
                      Check-in 30 menit sebelum keberangkatan
                    </Text>
                  </View>
                </View>

                <View style={styles.timelineLine} />

                <View style={styles.timelineItem}>
                  <View style={styles.timelineDot} />
                  <View style={styles.timelineInfo}>
                    <Text style={styles.timelineTime}>{arrTime} WIB</Text>
                    <Text style={styles.timelinePlace}>
                      Terminal Kedatangan {destination}
                    </Text>
                    <Text style={styles.timelineSub}>
                      Estimasi kedatangan tepat waktu
                    </Text>
                  </View>
                </View>
              </View>
            </View>

            {/* Facilities Card */}
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Fasilitas Kabin Premium</Text>
              <View style={styles.facilitiesGrid}>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Full AC Dual Blower Sejuk
                  </Text>
                </View>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Reclining Seat &amp; Sandaran Kaki (Leg Rest)
                  </Text>
                </View>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Colokan USB Fast Charger Tiap Kursi
                  </Text>
                </View>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Toilet Bersih Terawat di Dalam Kabin
                  </Text>
                </View>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Audio &amp; Karaoke Smart TV
                  </Text>
                </View>
                <View style={styles.facilityRow}>
                  <CheckCircle size={16} color={COLORS.brandRed} />
                  <Text style={styles.facilityName}>
                    Free Snack &amp; Air Mineral Botol
                  </Text>
                </View>
              </View>
            </View>

            {/* Crew & Safety Card */}
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Kru Pengemudi &amp; Keamanan</Text>
              <View style={styles.crewRow}>
                <View style={styles.crewAvatar}>
                  <User size={20} color="#FFFFFF" />
                </View>
                <View style={styles.crewInfo}>
                  <Text style={styles.crewName}>Kru Terlatih Tunggal Jaya</Text>
                  <Text style={styles.crewSub}>
                    Driver Utama Berlisensi Resmi &amp; Kondektur Ramah
                  </Text>
                </View>
                <ShieldCheck size={24} color={COLORS.accentGreen} />
              </View>
            </View>
          </View>
        )}

        {/* Tab 2: Deals & Tarif */}
        {activeTab === "deals" && (
          <View style={styles.sectionContainer}>
            <View style={styles.card}>
              <Text style={styles.cardTitle}>
                Penawaran &amp; Diskon Tersedia
              </Text>
              <View style={styles.dealItem}>
                <View style={styles.dealIconBox}>
                  <Tag size={18} color={COLORS.brandRed} />
                </View>
                <View style={styles.dealContent}>
                  <Text style={styles.dealTitle}>Kupon Early Bird 10%</Text>
                  <Text style={styles.dealDesc}>
                    Gunakan kode{" "}
                    <Text
                      style={{
                        color: COLORS.brandRed,
                        fontFamily: "PlusJakartaSans_700Bold",
                      }}
                    >
                      TJBERKAH
                    </Text>{" "}
                    saat checkout.
                  </Text>
                </View>
              </View>

              <View style={styles.dealItem}>
                <View style={styles.dealIconBox}>
                  <Sparkles size={18} color={COLORS.accentGold} />
                </View>
                <View style={styles.dealContent}>
                  <Text style={styles.dealTitle}>Cashback TJ Poin</Text>
                  <Text style={styles.dealDesc}>
                    Dapatkan 5.000 TJ Poin untuk setiap pemesanan tiket.
                  </Text>
                </View>
              </View>
            </View>
          </View>
        )}

        {/* Tab 3: Reviews */}
        {activeTab === "reviews" && (
          <View style={styles.sectionContainer}>
            <View style={styles.reviewsOverview}>
              <Text style={styles.reviewScoreBig}>4.8</Text>
              <View>
                <View
                  style={{
                    flexDirection: "row",
                    alignItems: "center",
                    gap: 2,
                    marginBottom: 2,
                  }}
                >
                  {[1, 2, 3, 4, 5].map((s) => (
                    <Star key={s} size={14} color="#D97706" fill="#D97706" />
                  ))}
                </View>
                <Text style={styles.reviewScoreCount}>
                  Berdasarkan 9.600+ ulasan penumpang
                </Text>
              </View>
            </View>

            {[
              {
                name: "Bambang Setyadi",
                score: 5,
                date: "2 hari yang lalu",
                text: "Unit Resi Bisma sangat bersih, AC dingin mantap, suspensi empuk banget tidak bikin mual!",
              },
              {
                name: "Siti Rahmawati",
                score: 5,
                date: "1 minggu yang lalu",
                text: "Driver bawa busnya sangat aman dan tepat waktu sampai di Kuningan. Recomended!",
              },
              {
                name: "Dwi Prasetyo",
                score: 4,
                date: "2 minggu yang lalu",
                text: "Kursi leg rest luas dan colokan USB berfungsi dengan baik. Sangat puas.",
              },
            ].map((rev, i) => (
              <View key={i} style={styles.reviewCard}>
                <View style={styles.reviewCardHeader}>
                  <Text style={styles.reviewCardAuthor}>{rev.name}</Text>
                  <Text style={styles.reviewCardDate}>{rev.date}</Text>
                </View>
                <View
                  style={{ flexDirection: "row", gap: 2, marginVertical: 4 }}
                >
                  {[1, 2, 3, 4, 5].map((s) => (
                    <Star
                      key={s}
                      size={12}
                      color={s <= rev.score ? "#D97706" : "#E2E8F0"}
                      fill={s <= rev.score ? "#D97706" : "transparent"}
                    />
                  ))}
                </View>
                <Text style={styles.reviewCardText}>{rev.text}</Text>
              </View>
            ))}
          </View>
        )}

        {/* Bottom spacer for floating action bar */}
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Red Action Bar (Phone 3 Mockup) */}
      <View style={styles.bottomBarWrapper} pointerEvents="box-none">
        <View style={styles.bottomBarContainer}>
          <View style={styles.bottomBarLeft}>
            <View style={styles.seatIconCircle}>
              <User size={16} color={COLORS.brandRed} />
            </View>
            <View>
              <Text style={styles.bottomSeatLabel}>1 Seat • {busType}</Text>
              <Text style={styles.bottomPriceValue}>Rp {price}</Text>
            </View>
          </View>

          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => {
              if (schedule?.id) {
                navigation.navigate("SeatSelection", {
                  scheduleId: schedule.id,
                  date: date || schedule?.selected_date,
                });
              }
            }}
            style={styles.bookNowBtn}
          >
            <Text style={styles.bookNowBtnText}>Pilih Kursi &gt;</Text>
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
  loadingContainer: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
    justifyContent: "center",
    alignItems: "center",
  },
  heroContainer: {
    height: 270,
    width: "100%",
    position: "relative",
  },
  heroImage: {
    width: "100%",
    height: "100%",
  },
  heroGradient: {
    ...StyleSheet.absoluteFillObject,
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingBottom: 16,
  },
  navBar: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingTop: Platform.OS === "android" ? 12 : 6,
  },
  navIconBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "rgba(255, 255, 255, 0.25)",
    justifyContent: "center",
    alignItems: "center",
  },
  navTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 16,
    color: "#FFFFFF",
  },
  routeHeaderOverlay: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-end",
  },
  routeHeaderLeft: {},
  badgeLine: {
    alignSelf: "flex-start",
    backgroundColor: COLORS.brandRed,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 8,
    marginBottom: 4,
  },
  badgeLineText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  routeHeaderTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 22,
    color: "#FFFFFF",
    letterSpacing: -0.3,
  },
  routeHeaderSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 13,
    color: "rgba(255, 255, 255, 0.85)",
    marginTop: 2,
  },
  routeHeaderRight: {
    alignItems: "flex-end",
  },
  starsRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 4,
    backgroundColor: "rgba(0, 0, 0, 0.45)",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 10,
    marginBottom: 4,
  },
  starsText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  routeHeaderPrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: "#FFFFFF",
  },
  routeHeaderPriceSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "rgba(255, 255, 255, 0.8)",
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
  },
  tabsRow: {
    flexDirection: "row",
    gap: 10,
    marginBottom: 20,
  },
  tabPill: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 22,
  },
  tabPillActive: {
    backgroundColor: COLORS.brandRed,
  },
  tabPillInactive: {
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  tabPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
  },
  tabPillTextActive: {
    color: "#FFFFFF",
  },
  tabPillTextInactive: {
    color: "#4B5563",
  },
  sectionContainer: {
    gap: 16,
  },
  card: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.05,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  cardTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 16,
    color: "#111827",
    marginBottom: 14,
  },
  specsGrid: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 10,
  },
  specBox: {
    width: "47%",
    backgroundColor: "#F1F4F8",
    borderRadius: 12,
    padding: 12,
  },
  specLabel: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#6B7280",
    marginBottom: 4,
  },
  specValue: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
  },
  timelineWrapper: {
    paddingLeft: 4,
  },
  timelineItem: {
    flexDirection: "row",
    gap: 14,
    alignItems: "flex-start",
  },
  timelineDotActive: {
    width: 14,
    height: 14,
    borderRadius: 7,
    backgroundColor: COLORS.brandRed,
    marginTop: 2,
  },
  timelineDot: {
    width: 14,
    height: 14,
    borderRadius: 7,
    backgroundColor: COLORS.accentGold,
    marginTop: 2,
  },
  timelineLine: {
    width: 2,
    height: 24,
    backgroundColor: "#CBD5E1",
    marginLeft: 6,
    marginVertical: 4,
  },
  timelineInfo: {
    flex: 1,
  },
  timelineTime: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  timelinePlace: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 14,
    color: "#111827",
    marginTop: 2,
  },
  timelineSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  facilitiesGrid: {
    gap: 10,
  },
  facilityRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  facilityName: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#4B5563",
  },
  crewRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
  },
  crewAvatar: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: "#EEF2F6",
    justifyContent: "center",
    alignItems: "center",
  },
  crewInfo: {
    flex: 1,
  },
  crewName: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
  },
  crewSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#6B7280",
    marginTop: 2,
  },
  dealItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    padding: 14,
    borderRadius: 14,
    marginBottom: 10,
  },
  dealIconBox: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: "rgba(230, 0, 35, 0.1)",
    justifyContent: "center",
    alignItems: "center",
  },
  dealContent: {
    flex: 1,
  },
  dealTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
  },
  dealDesc: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#4B5563",
    marginTop: 2,
  },
  reviewsOverview: {
    flexDirection: "row",
    alignItems: "center",
    gap: 16,
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  reviewScoreBig: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 36,
    color: "#111827",
  },
  reviewScoreStars: {
    color: "#D97706",
    fontSize: 16,
  },
  reviewScoreCount: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#6B7280",
    marginTop: 2,
  },
  reviewCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  reviewCardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 4,
  },
  reviewCardAuthor: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
  },
  reviewCardDate: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#9CA3AF",
  },
  reviewCardStars: {
    color: "#D97706",
    fontSize: 12,
    marginBottom: 6,
  },
  reviewCardText: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 13,
    color: "#4B5563",
    lineHeight: 19,
  },
  bottomBarWrapper: {
    position: "absolute",
    bottom: Platform.OS === "ios" ? 28 : 20,
    left: 20,
    right: 20,
  },
  bottomBarContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: COLORS.brandRed,
    paddingVertical: 10,
    paddingLeft: 16,
    paddingRight: 10,
    borderRadius: 36,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 8 },
        shadowOpacity: 0.35,
        shadowRadius: 14,
      },
      android: {
        elevation: 8,
      },
    }),
  },
  bottomBarLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  seatIconCircle: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: "#FFFFFF",
    justifyContent: "center",
    alignItems: "center",
  },
  bottomSeatLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "rgba(255, 255, 255, 0.85)",
  },
  bottomPriceValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#FFFFFF",
  },
  bookNowBtn: {
    backgroundColor: "#FFFFFF",
    paddingVertical: 12,
    paddingHorizontal: 22,
    borderRadius: 28,
  },
  bookNowBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: COLORS.brandRed,
  },
});
