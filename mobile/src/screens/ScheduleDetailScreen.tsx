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
  Modal,
  TextInput,
  KeyboardAvoidingView,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { LinearGradient } from "expo-linear-gradient";
import { useNavigation, useRoute } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import AsyncStorage from "@react-native-async-storage/async-storage";
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
  PlusCircle,
  X,
  Send,
  ThumbsUp,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import { useCustomAlert } from "../context/AlertContext";
import { useAuth } from "../context/AuthContext";
import apiClient from "../api/client";
import { formatIndonesianTime } from "../utils/format";

const { width } = Dimensions.get("window");

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

interface ReviewItem {
  id: string;
  name: string;
  score: number;
  date: string;
  text: string;
  isUser?: boolean;
}

const DEFAULT_REVIEWS: ReviewItem[] = [
  {
    id: "rev-1",
    name: "Bambang Setyadi",
    score: 5,
    date: "2 hari yang lalu",
    text: "Unit armada sangat bersih dan wangi, AC dingin mantap, suspensi udara empuk banget tidak bikin mual di tol Cipali!",
  },
  {
    id: "rev-2",
    name: "Siti Rahmawati",
    score: 5,
    date: "1 minggu yang lalu",
    text: "Driver bawa busnya sangat tenang, aman, dan tepat waktu sampai di Terminal Kuningan. Sangat direkomendasikan!",
  },
  {
    id: "rev-3",
    name: "Dwi Prasetyo",
    score: 4,
    date: "2 minggu yang lalu",
    text: "Kursi leg rest luas dan colokan USB berfungsi dengan baik untuk charger HP sepanjang jalan. Sangat puas.",
  },
  {
    id: "rev-4",
    name: "Ahmad Fauzi",
    score: 5,
    date: "3 minggu yang lalu",
    text: "Kru bus ramah dan sigap membantu bagasi. Rehat servis makan prasmanan juga enak dan bersih.",
  },
];

export default function ScheduleDetailScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<any>();
  const { scheduleId, date } = route.params || { scheduleId: 1 };
  const { user } = useAuth();
  const { showSuccess, showError, showInfo } = useCustomAlert();

  const [activeTab, setActiveTab] = useState<"details" | "deals" | "reviews">(
    "details",
  );
  const [schedule, setSchedule] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  // Reviews and rating state
  const [reviews, setReviews] = useState<ReviewItem[]>(DEFAULT_REVIEWS);
  const [reviewModalVisible, setReviewModalVisible] = useState(false);
  const [newRating, setNewRating] = useState(5);
  const [reviewerName, setReviewerName] = useState(user?.name || "");
  const [reviewComment, setReviewComment] = useState("");
  const [submittingReview, setSubmittingReview] = useState(false);

  useEffect(() => {
    fetchDetail();
    loadPersistedReviews();
  }, [scheduleId, date]);

  useEffect(() => {
    if (user?.name && !reviewerName) {
      setReviewerName(user.name);
    }
  }, [user]);

  const loadPersistedReviews = async () => {
    try {
      const stored = await AsyncStorage.getItem(`@bus_reviews_${scheduleId}`);
      if (stored) {
        const parsed = JSON.parse(stored);
        if (Array.isArray(parsed) && parsed.length > 0) {
          setReviews([...parsed, ...DEFAULT_REVIEWS]);
        }
      }
    } catch (e) {
      console.log("Error loading persisted reviews:", e);
    }
  };

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

  const calculateAverageRating = () => {
    if (reviews.length === 0) return "4.8";
    const sum = reviews.reduce((acc, curr) => acc + curr.score, 0);
    return (sum / reviews.length).toFixed(1);
  };

  const handleOpenReviewModal = () => {
    setReviewerName(user?.name || reviewerName || "");
    setNewRating(5);
    setReviewComment("");
    setReviewModalVisible(true);
  };

  const handleSubmitReview = async () => {
    const trimmedName = (reviewerName || user?.name || "").trim();
    const trimmedComment = reviewComment.trim();

    if (!trimmedName) {
      showError(
        "Nama Diperlukan",
        "Silakan masukkan nama Anda sebelum mengirim ulasan.",
      );
      return;
    }

    if (!trimmedComment) {
      showError(
        "Ulasan Masih Kosong",
        "Silakan tuliskan beberapa kata tentang pengalaman perjalanan Anda.",
      );
      return;
    }

    try {
      setSubmittingReview(true);

      const newReviewItem: ReviewItem = {
        id: `user-rev-${Date.now()}`,
        name: trimmedName,
        score: newRating,
        date: "Hari ini (Terverifikasi)",
        text: trimmedComment,
        isUser: true,
      };

      const updated = [newReviewItem, ...reviews.filter((r) => !r.isUser)];
      setReviews(updated);

      // Save user review to AsyncStorage
      const userReviews = updated.filter((r) => r.isUser);
      await AsyncStorage.setItem(
        `@bus_reviews_${scheduleId}`,
        JSON.stringify(userReviews),
      );

      setReviewModalVisible(false);
      setReviewComment("");
      showSuccess(
        "Ulasan Berhasil Dikirim! ⭐",
        "Terima kasih atas rating dan ulasan Anda. Masukan Anda sangat berharga bagi peningkatan layanan PO Tunggal Jaya.",
      );
    } catch (e) {
      console.log("Error saving review:", e);
      showError(
        "Gagal Mengirim",
        "Terjadi kendala saat menyimpan ulasan Anda.",
      );
    } finally {
      setSubmittingReview(false);
    }
  };

  const averageRating = calculateAverageRating();
  const totalReviewCount = 9600 + reviews.filter((r) => r.isUser).length;

  const tabs = [
    { id: "details", label: "Spesifikasi", icon: FileText },
    { id: "deals", label: "Promo & Tarif", icon: Tag },
    { id: "reviews", label: `Ulasan (${averageRating})`, icon: MessageSquare },
  ];

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={COLORS.brandRed} />
      </View>
    );
  }

  const busName = schedule?.bus?.name || "Resi Bisma";
  const busType = schedule?.bus?.bus_type || schedule?.bus?.type || "Executive";
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

  const getRatingSentiment = (star: number) => {
    switch (star) {
      case 5:
        return "5 Bintang • Sangat Puas & Nyaman ⭐⭐⭐⭐⭐";
      case 4:
        return "4 Bintang • Puas & Bagus ⭐⭐⭐⭐";
      case 3:
        return "3 Bintang • Cukup Baik ⭐⭐⭐";
      case 2:
        return "2 Bintang • Kurang Memuaskan ⭐⭐";
      case 1:
        return "1 Bintang • Perlu Perbaikan ⭐";
      default:
        return "Beri Nilai Bintang";
    }
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
            "rgba(17, 24, 39, 0.95)",
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
              <Text style={styles.routeHeaderTitle} numberOfLines={1}>
                {busName}
              </Text>
              <Text style={styles.routeHeaderSub} numberOfLines={1}>
                {origin} ↔ {destination}
              </Text>
            </View>

            <View style={styles.routeHeaderRight}>
              <View style={styles.starsRow}>
                <Star size={12} color="#FFB800" fill="#FFB800" />
                <Text style={styles.starsText}>
                  {averageRating} ({(totalReviewCount / 1000).toFixed(1)}k)
                </Text>
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
        {/* Horizontal Tab Switcher Pills */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.tabsScrollContent}
          style={styles.tabsScrollView}
        >
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
        </ScrollView>

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
                      Titik Kumpul &amp; Boarding Penumpang
                    </Text>
                  </View>
                </View>

                <View style={styles.timelineLine} />

                <View style={styles.timelineItem}>
                  <View style={styles.timelineDotDest} />
                  <View style={styles.timelineInfo}>
                    <Text style={styles.timelineTime}>{arrTime} WIB</Text>
                    <Text style={styles.timelinePlace}>
                      Terminal Tujuan {destination}
                    </Text>
                    <Text style={styles.timelineSub}>
                      Kedatangan Akhir Penumpang
                    </Text>
                  </View>
                </View>
              </View>
            </View>

            {/* Facilities Card */}
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Fasilitas Armada Termasuk</Text>
              <View style={styles.facilitiesGrid}>
                {[
                  {
                    icon: Armchair,
                    label: "Reclining Seat",
                    desc: "Kursi empuk + leg rest",
                  },
                  {
                    icon: Wifi,
                    label: "Free High-Speed Wi-Fi",
                    desc: "Internet stabil",
                  },
                  {
                    icon: Tv,
                    label: "Audio Video on Demand",
                    desc: "Hiburan TV sentral",
                  },
                  {
                    icon: Coffee,
                    label: "Servis Makan Gratis",
                    desc: "1x prasmanan lezat",
                  },
                  {
                    icon: ShieldCheck,
                    label: "Air Suspension",
                    desc: "Suspensi udara lembut",
                  },
                  {
                    icon: CheckCircle,
                    label: "Port USB Charger",
                    desc: "Di setiap baris kursi",
                  },
                ].map((fac, idx) => {
                  const FacIcon = fac.icon;
                  return (
                    <View key={idx} style={styles.facilityItem}>
                      <View style={styles.facilityIconCircle}>
                        <FacIcon size={18} color={COLORS.brandRed} />
                      </View>
                      <View style={{ flex: 1 }}>
                        <Text style={styles.facilityName}>{fac.label}</Text>
                        <Text style={styles.facilityDesc}>{fac.desc}</Text>
                      </View>
                    </View>
                  );
                })}
              </View>
            </View>
          </View>
        )}

        {/* Tab 2: Deals & Promo */}
        {activeTab === "deals" && (
          <View style={styles.sectionContainer}>
            <View style={styles.card}>
              <Text style={styles.cardTitle}>Promo &amp; Penawaran Aktif</Text>
              <View style={styles.dealItem}>
                <View style={styles.dealIconBox}>
                  <Tag size={18} color={COLORS.brandRed} />
                </View>
                <View style={styles.dealContent}>
                  <Text style={styles.dealTitle}>Diskon Member VIP 10%</Text>
                  <Text style={styles.dealDesc}>
                    Gunakan kode kupon{" "}
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
                    Dapatkan +5.000 TJ Poin untuk setiap pemesanan tiket resmi.
                  </Text>
                </View>
              </View>
            </View>
          </View>
        )}

        {/* Tab 3: Rating & Reviews */}
        {activeTab === "reviews" && (
          <View style={styles.sectionContainer}>
            {/* Overview Card */}
            <View style={styles.reviewsOverview}>
              <View style={styles.reviewScoreBox}>
                <Text style={styles.reviewScoreBig}>{averageRating}</Text>
                <View style={{ flexDirection: "row", gap: 3, marginTop: 4 }}>
                  {[1, 2, 3, 4, 5].map((s) => (
                    <Star
                      key={s}
                      size={14}
                      color="#D97706"
                      fill={
                        s <= Math.round(Number(averageRating))
                          ? "#D97706"
                          : "transparent"
                      }
                    />
                  ))}
                </View>
                <Text style={styles.reviewScoreCount}>
                  Berdasarkan {totalReviewCount.toLocaleString("id-ID")}+ ulasan
                </Text>
              </View>

              {/* Write Review Button */}
              <TouchableOpacity
                activeOpacity={0.85}
                onPress={handleOpenReviewModal}
                style={styles.writeReviewBtn}
              >
                <PlusCircle size={16} color="#FFFFFF" />
                <Text style={styles.writeReviewBtnText}>Tulis Ulasan</Text>
              </TouchableOpacity>
            </View>

            {/* Review Cards List */}
            {reviews.map((rev) => (
              <View
                key={rev.id}
                style={[
                  styles.reviewCard,
                  rev.isUser && styles.reviewCardUserHighlight,
                ]}
              >
                <View style={styles.reviewCardHeader}>
                  <View
                    style={{
                      flexDirection: "row",
                      alignItems: "center",
                      gap: 8,
                    }}
                  >
                    <View style={styles.reviewerAvatarBadge}>
                      <Text style={styles.reviewerAvatarText}>
                        {rev.name.substring(0, 2).toUpperCase()}
                      </Text>
                    </View>
                    <View>
                      <Text style={styles.reviewCardAuthor}>{rev.name}</Text>
                      {rev.isUser && (
                        <View style={styles.userVerifiedBadge}>
                          <ThumbsUp size={10} color="#059669" />
                          <Text style={styles.userVerifiedText}>
                            Ulasan Anda
                          </Text>
                        </View>
                      )}
                    </View>
                  </View>
                  <Text style={styles.reviewCardDate}>{rev.date}</Text>
                </View>

                <View
                  style={{
                    flexDirection: "row",
                    gap: 3,
                    marginVertical: 8,
                  }}
                >
                  {[1, 2, 3, 4, 5].map((s) => (
                    <Star
                      key={s}
                      size={13}
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

      {/* Floating Bottom Red Action Bar */}
      <View style={styles.bottomBarWrapper} pointerEvents="box-none">
        <View style={styles.bottomBarContainer}>
          <View style={styles.bottomBarLeft}>
            <View style={styles.seatIconCircle}>
              <User size={16} color={COLORS.brandRed} />
              <User size={16} color={COLORS.brandBlue} />
            </View>
            <View>
              <Text style={styles.bottomSeatLabel}>1 Kursi • {busType}</Text>
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

      {/* MODAL TULIS ULASAN & RATING */}
      <Modal
        visible={reviewModalVisible}
        transparent
        animationType="slide"
        onRequestClose={() => setReviewModalVisible(false)}
      >
        <KeyboardAvoidingView
          behavior={Platform.OS === "ios" ? "padding" : undefined}
          style={styles.modalBackdrop}
        >
          <View style={styles.modalCard}>
            {/* Modal Header */}
            <View style={styles.modalHeader}>
              <View>
                <Text style={styles.modalTitle}>Beri Rating &amp; Ulasan</Text>
                <Text style={styles.modalSub}>
                  Armada: {busName} ({busType})
                </Text>
              </View>
              <TouchableOpacity
                activeOpacity={0.7}
                onPress={() => setReviewModalVisible(false)}
                style={styles.closeModalBtn}
              >
                <X size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            <ScrollView
              showsVerticalScrollIndicator={false}
              keyboardShouldPersistTaps="handled"
            >
              {/* Star Rating Selector */}
              <View style={styles.starRatingBox}>
                <Text style={styles.starSelectLabel}>
                  Berapa bintang untuk armada ini?
                </Text>
                <View style={styles.starInteractiveRow}>
                  {[1, 2, 3, 4, 5].map((star) => {
                    const isFilled = star <= newRating;
                    return (
                      <TouchableOpacity
                        key={star}
                        activeOpacity={0.7}
                        onPress={() => setNewRating(star)}
                        style={styles.starTouchItem}
                      >
                        <Star
                          size={32}
                          color={isFilled ? "#D97706" : "#CBD5E1"}
                          fill={isFilled ? "#F59E0B" : "transparent"}
                        />
                      </TouchableOpacity>
                    );
                  })}
                </View>
                <Text style={styles.sentimentText}>
                  {getRatingSentiment(newRating)}
                </Text>
              </View>

              {/* Passenger Name Input */}
              <View style={styles.inputGroup}>
                <Text style={styles.inputLabel}>Nama Penumpang</Text>
                <TextInput
                  placeholder="Masukkan nama Anda..."
                  placeholderTextColor="#94A3B8"
                  value={reviewerName}
                  onChangeText={setReviewerName}
                  style={styles.textInput}
                />
              </View>

              {/* Review Description */}
              <View style={styles.inputGroup}>
                <Text style={styles.inputLabel}>Ulasan Perjalanan Anda</Text>
                <TextInput
                  placeholder="Ceritakan kenyamanan bus, pelayanan kru, kebersihan, atau ketepatan waktu..."
                  placeholderTextColor="#94A3B8"
                  value={reviewComment}
                  onChangeText={setReviewComment}
                  multiline
                  numberOfLines={4}
                  textAlignVertical="top"
                  style={[styles.textInput, styles.textAreaInput]}
                />
              </View>

              {/* Submit Button */}
              <TouchableOpacity
                activeOpacity={0.85}
                disabled={submittingReview}
                onPress={handleSubmitReview}
                style={[
                  styles.submitReviewBtn,
                  submittingReview && { opacity: 0.6 },
                ]}
              >
                {submittingReview ? (
                  <ActivityIndicator size="small" color="#FFFFFF" />
                ) : (
                  <>
                    <Send
                      size={16}
                      color="#FFFFFF"
                      style={{ marginRight: 8 }}
                    />
                    <Text style={styles.submitReviewBtnText}>
                      Kirim Ulasan &amp; Rating
                    </Text>
                  </>
                )}
              </TouchableOpacity>
            </ScrollView>
          </View>
        </KeyboardAvoidingView>
      </Modal>
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
    gap: 10,
  },
  routeHeaderLeft: {
    flex: 1,
    minWidth: 0,
    paddingRight: 6,
  },
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
    fontSize: 20,
    color: "#FFFFFF",
    letterSpacing: -0.3,
  },
  routeHeaderSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12.5,
    color: "rgba(255, 255, 255, 0.85)",
    marginTop: 2,
  },
  routeHeaderRight: {
    alignItems: "flex-end",
    flexShrink: 0,
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
    fontSize: 16.5,
    color: "#FFFFFF",
  },
  routeHeaderPriceSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11.5,
    color: "rgba(255, 255, 255, 0.85)",
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
  },
  tabsScrollView: {
    marginBottom: 16,
  },
  tabsScrollContent: {
    flexDirection: "row",
    gap: 10,
    paddingRight: 16,
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
    gap: 12,
  },
  specBox: {
    width: "48%",
    backgroundColor: "#F8FAFC",
    padding: 12,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#F1F5F9",
  },
  specLabel: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#6B7280",
    marginBottom: 2,
  },
  specValue: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#111827",
  },
  timelineWrapper: {
    position: "relative",
    paddingLeft: 10,
  },
  timelineLine: {
    position: "absolute",
    top: 24,
    bottom: 24,
    left: 17,
    width: 2,
    backgroundColor: "#E2E8F0",
  },
  timelineItem: {
    flexDirection: "row",
    alignItems: "flex-start",
    gap: 14,
    marginVertical: 8,
  },
  timelineDotActive: {
    width: 16,
    height: 16,
    borderRadius: 8,
    backgroundColor: COLORS.brandRed,
    borderWidth: 3,
    borderColor: "#FECACA",
    marginTop: 2,
  },
  timelineDotDest: {
    width: 16,
    height: 16,
    borderRadius: 8,
    backgroundColor: "#059669",
    borderWidth: 3,
    borderColor: "#A7F3D0",
    marginTop: 2,
  },
  timelineInfo: {
    flex: 1,
  },
  timelineTime: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#111827",
  },
  timelinePlace: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#374151",
    marginTop: 1,
  },
  timelineSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11.5,
    color: "#9CA3AF",
    marginTop: 1,
  },
  facilitiesGrid: {
    gap: 12,
  },
  facilityItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    backgroundColor: "#F8FAFC",
    padding: 12,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: "#F1F5F9",
  },
  facilityIconCircle: {
    width: 38,
    height: 38,
    borderRadius: 12,
    backgroundColor: "rgba(220, 38, 38, 0.08)",
    justifyContent: "center",
    alignItems: "center",
  },
  facilityName: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#111827",
  },
  facilityDesc: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11.5,
    color: "#6B7280",
    marginTop: 1,
  },
  dealItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    padding: 14,
    borderRadius: 14,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#F1F5F9",
    marginBottom: 10,
  },
  dealIconBox: {
    width: 36,
    height: 36,
    borderRadius: 10,
    backgroundColor: "rgba(220, 38, 38, 0.08)",
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
    justifyContent: "space-between",
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  reviewScoreBox: {
    flex: 1,
  },
  reviewScoreBig: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 32,
    color: "#111827",
  },
  reviewScoreCount: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11.5,
    color: "#6B7280",
    marginTop: 4,
  },
  writeReviewBtn: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    backgroundColor: COLORS.brandRed,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 12,
  },
  writeReviewBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
  reviewCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  reviewCardUserHighlight: {
    borderColor: "#BFDBFE",
    backgroundColor: "#F8FAFC",
  },
  reviewCardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  reviewerAvatarBadge: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#EFF6FF",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#DBEAFE",
  },
  reviewerAvatarText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: "#2563EB",
  },
  reviewCardAuthor: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#111827",
  },
  userVerifiedBadge: {
    flexDirection: "row",
    alignItems: "center",
    gap: 3,
    marginTop: 1,
  },
  userVerifiedText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#059669",
  },
  reviewCardDate: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#9CA3AF",
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
    left: 16,
    right: 16,
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
    bottom: 0,
    left: 0,
    right: 0,
    alignItems: "center",
    paddingHorizontal: 16,
    paddingBottom: Platform.OS === "ios" ? 28 : 16,
  },
  bottomBarContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: COLORS.brandRed,
    backgroundColor: COLORS.brandBlue,
    paddingVertical: 10,
    paddingLeft: 16,
    paddingRight: 10,
    borderRadius: 36,
    width: "100%",
    maxWidth: 680,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowColor: COLORS.brandBlue,
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
    color: COLORS.brandBlue,
  },
  modalBackdrop: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.7)",
    justifyContent: "flex-end",
  },
  modalCard: {
    backgroundColor: "#FFFFFF",
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    padding: 20,
    maxHeight: "85%",
  },
  modalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-start",
    marginBottom: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  modalTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#1E293B",
  },
  modalSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
    marginTop: 2,
  },
  closeModalBtn: {
    padding: 6,
    backgroundColor: "#F1F5F9",
    borderRadius: 12,
  },
  starRatingBox: {
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    padding: 16,
    borderRadius: 16,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  starSelectLabel: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#334155",
    marginBottom: 10,
  },
  starInteractiveRow: {
    flexDirection: "row",
    gap: 8,
    marginVertical: 4,
  },
  starTouchItem: {
    padding: 4,
  },
  sentimentText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#D97706",
    marginTop: 8,
  },
  inputGroup: {
    marginBottom: 14,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#334155",
    marginBottom: 6,
  },
  textInput: {
    backgroundColor: "#F8FAFC",
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 14,
    paddingVertical: 10,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 13,
    color: "#1E293B",
  },
  textAreaInput: {
    height: 90,
    textAlignVertical: "top",
  },
  submitReviewBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandRed,
    paddingVertical: 14,
    borderRadius: 14,
    marginTop: 8,
    marginBottom: 20,
  },
  submitReviewBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
});
