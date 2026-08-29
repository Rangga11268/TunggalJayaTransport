import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Modal,
  Platform,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { COLORS } from "../theme/colors";
import { ScreenHeader } from "../components/ScreenHeader";
import { useRewards, RewardItem } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";
import {
  PromoVoucherIcon,
  FreeMealBuffetIcon,
  ExecutiveLeatherSeatsIcon,
  RewardsLoyaltyIcon,
} from "../components/ServiceIcons";
import {
  Sparkles,
  Gift,
  Crown,
  CheckCircle2,
  Calendar,
  Tag,
  Coffee,
  ShoppingBag,
  Armchair,
  ArrowRight,
  Clock,
  History,
  X,
  Zap,
  Ticket,
  Compass,
} from "lucide-react-native";

const CATALOG_ITEMS: RewardItem[] = [
  {
    id: "rw-1",
    title: "Voucher Diskon Rp 25.000",
    category: "voucher",
    pointsRequired: 500,
    description: "Potongan langsung Rp 25.000 untuk semua rute AKAP",
    voucherCode: "TJREWARD25",
  },
  {
    id: "rw-2",
    title: "Voucher Diskon Rp 50.000",
    category: "voucher",
    pointsRequired: 950,
    description: "Potongan hemat Rp 50.000 untuk semua kelas armada",
    voucherCode: "TJREWARD50",
  },
  {
    id: "rw-3",
    title: "Snack & Minuman Gratis",
    category: "snack",
    pointsRequired: 300,
    description: "Klaim paket snack premium & air mineral di dalam bus",
    voucherCode: "TJ-SNACK-VIP",
  },
  {
    id: "rw-4",
    title: "Gratis Kursi VIP Baris Depan",
    category: "seat",
    pointsRequired: 400,
    description: "Pilih kursi baris 1 & 2 tanpa biaya tambahan",
    voucherCode: "TJ-VIPSEAT",
  },
  {
    id: "rw-5",
    title: "Kaos Eksklusif PO Tunggal Jaya",
    category: "merchandise",
    pointsRequired: 2500,
    description:
      "Merchandise original Cotton Combed 30s official PO Tunggal Jaya",
    voucherCode: "TJ-MERCH-TEE",
  },
];

const DAILY_STREAK = [
  { day: 1, points: 10 },
  { day: 2, points: 20 },
  { day: 3, points: 30 },
  { day: 4, points: 40 },
  { day: 5, points: 50 },
  { day: 6, points: 75 },
  { day: 7, points: 150 },
];

export default function RewardsScreen({ navigation }: any) {
  const {
    points,
    tier,
    streakDays,
    hasClaimedToday,
    transactions,
    claimDailyCheckIn,
    redeemReward,
  } = useRewards();
  const { showSuccess, showError, showWarning, showInfo, showAlert } =
    useCustomAlert();

  const [selectedCategory, setSelectedCategory] = useState<
    "all" | "voucher" | "snack" | "merchandise" | "seat"
  >("all");
  const [claiming, setClaiming] = useState(false);
  const [redeemingItem, setRedeemingItem] = useState<RewardItem | null>(null);
  const [isHistoryModalOpen, setIsHistoryModalOpen] = useState(false);

  const handleClaim = async () => {
    if (hasClaimedToday) {
      showInfo(
        "Sudah Diklaim",
        "Anda sudah mengklaim bonus check-in hari ini. Kembali lagi besok!",
      );
      return;
    }
    try {
      setClaiming(true);
      const ok = await claimDailyCheckIn();
      if (ok) {
        showSuccess(
          "Klaim Berhasil! 🎉",
          "Selamat! Bonus poin check-in harian telah ditambahkan ke akun Anda.",
        );
      }
    } finally {
      setClaiming(false);
    }
  };

  const handleConfirmRedeem = async () => {
    if (!redeemingItem) return;
    const item = redeemingItem;
    setRedeemingItem(null);
    if (points < item.pointsRequired) {
      showError(
        "Poin Tidak Cukup",
        `Anda membutuhkan ${item.pointsRequired} Poin untuk hadiah ini (Poin Anda: ${points}).`,
      );
      return;
    }
    const ok = await redeemReward(item);
    if (ok) {
      showAlert({
        title: "Penukaran Berhasil! 🎁",
        message: `Hadiah '${item.title}' berhasil ditukarkan.\nKode Voucher: ${item.voucherCode || "TJ-REWARD"}\nGunakan saat checkout atau tunjukkan ke kru armada.`,
        type: "success",
        buttons: [
          {
            text: "Lihat Riwayat",
            style: "cancel",
            onPress: () => setIsHistoryModalOpen(true),
          },
          {
            text: "Gunakan Sekarang",
            style: "default",
            onPress: () => navigation.navigate("Schedules"),
          },
        ],
      });
    }
  };

  const filteredCatalog =
    selectedCategory === "all"
      ? CATALOG_ITEMS
      : CATALOG_ITEMS.filter((item) => item.category === selectedCategory);

  const completedStreakDays = hasClaimedToday
    ? streakDays % 7 === 0
      ? 7
      : streakDays % 7
    : (streakDays - 1) % 7;
  const progressPercent = Math.min(100, Math.round((points / 5000) * 100));

  return (
    <View style={styles.container}>
      <ScreenHeader
        title="TJ Rewards & Loyalitas"
        subtitle="Kumpulkan Poin & Tukar Berbagai Hadiah Menarik"
        showBack={true}
        onBack={() => navigation.goBack()}
        rightElement={
          <TouchableOpacity
            style={styles.historyBtn}
            onPress={() => setIsHistoryModalOpen(true)}
            activeOpacity={0.8}
          >
            <History
              size={16}
              color={COLORS.brandBlue}
              style={{ marginRight: 4 }}
            />
            <Text style={styles.historyBtnText}>Riwayat</Text>
          </TouchableOpacity>
        }
      />

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* 1. VIP MEMBERSHIP CARD */}
        <LinearGradient
          colors={["#0F2B5C", "#1E40AF", "#3B82F6"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={styles.vipCard}
        >
          <View style={styles.vipTopRow}>
            <View style={styles.tierBadge}>
              <Crown size={14} color="#FBBF24" style={{ marginRight: 6 }} />
              <Text style={styles.tierBadgeText}>
                {tier.toUpperCase()} MEMBER
              </Text>
            </View>
            <Text style={styles.vipCardNumber}>ID: TJ-MEM-8829</Text>
          </View>

          <View style={styles.pointsBalanceSection}>
            <Text style={styles.pointsLabel}>Total TJ Points Anda</Text>
            <View style={styles.pointsValueRow}>
              <Text style={styles.pointsNumber}>
                {points.toLocaleString("id-ID")}
              </Text>
              <Text style={styles.pointsUnitText}>Poin</Text>
            </View>
          </View>

          {/* Tier Progress Bar */}
          <View style={styles.tierProgressContainer}>
            <View style={styles.tierProgressLabels}>
              <Text style={styles.tierProgressText}>
                {points < 5000
                  ? `Butuh ${(5000 - points).toLocaleString("id-ID")} poin lagi ke Platinum`
                  : "Status Member Tertinggi (Platinum VIP)"}
              </Text>
              <Text style={styles.tierProgressPercent}>{progressPercent}%</Text>
            </View>
            <View style={styles.progressBarTrack}>
              <View
                style={[
                  styles.progressBarFill,
                  { width: `${progressPercent}%` },
                ]}
              />
            </View>
          </View>
        </LinearGradient>

        {/* 2. DAILY CHECK-IN STREAK SECTION */}
        <View style={styles.sectionBox}>
          <View style={styles.sectionHeaderRow}>
            <View style={styles.sectionTitleCol}>
              <Text style={styles.sectionTitle}>Check-In Harian Berhadiah</Text>
              <Text style={styles.sectionSub}>
                Login setiap hari &amp; raih hingga 150 poin + kupon gratis!
              </Text>
            </View>
            <View style={styles.streakCountBadge}>
              <Zap size={13} color="#D97706" style={{ marginRight: 4 }} />
              <Text style={styles.streakCountText}>
                {streakDays} Hari Streak
              </Text>
            </View>
          </View>

          {/* 7-Days Streak Ribbon */}
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.streakScroll}
          >
            {DAILY_STREAK.map((item) => {
              const isPassed = item.day <= completedStreakDays;
              const isToday =
                !hasClaimedToday && item.day === completedStreakDays + 1;
              return (
                <View
                  key={item.day}
                  style={[
                    styles.streakDayCard,
                    isPassed && styles.streakDayCardPassed,
                    isToday && styles.streakDayCardToday,
                  ]}
                >
                  <Text
                    style={[
                      styles.streakDayTitle,
                      isPassed
                        ? styles.streakDayTitlePassed
                        : isToday
                          ? styles.streakDayTitleToday
                          : undefined,
                    ]}
                  >
                    Hari {item.day}
                  </Text>
                  <View
                    style={[
                      styles.streakCoinCircle,
                      isPassed
                        ? styles.streakCoinCirclePassed
                        : isToday
                          ? styles.streakCoinCircleToday
                          : undefined,
                    ]}
                  >
                    {isPassed ? (
                      <CheckCircle2 size={16} color="#FFFFFF" />
                    ) : (
                      <Text
                        style={[
                          styles.streakCoinText,
                          isToday && styles.streakCoinTextToday,
                        ]}
                      >
                        +{item.points}
                      </Text>
                    )}
                  </View>
                  <Text
                    style={[
                      styles.streakSubLabel,
                      isPassed && styles.streakSubLabelPassed,
                    ]}
                  >
                    {isPassed ? "Selesai" : isToday ? "Hari Ini" : "Poin"}
                  </Text>
                </View>
              );
            })}
          </ScrollView>

          {/* Claim Button */}
          <TouchableOpacity
            style={[
              styles.claimActionBtn,
              hasClaimedToday && styles.claimActionBtnDisabled,
            ]}
            disabled={hasClaimedToday || claiming}
            onPress={handleClaim}
            activeOpacity={0.85}
          >
            {claiming ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <>
                <Sparkles
                  size={16}
                  color="#FFFFFF"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.claimActionBtnText}>
                  {hasClaimedToday
                    ? "Bonus Hari Ini Sudah Diklaim"
                    : "Klaim Bonus Poin Hari Ini"}
                </Text>
              </>
            )}
          </TouchableOpacity>
        </View>

        {/* 3. REDEEM REWARDS CATALOG */}
        <View style={styles.sectionBox}>
          <Text style={styles.sectionTitle}>Tukar Hadiah &amp; Voucher</Text>
          <Text style={styles.sectionSub}>
            Gunakan poin Anda untuk mendapatkan potongan tiket &amp; fasilitas
            istimewa
          </Text>

          {/* Category Filter Chips */}
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.categoryChipsRow}
          >
            {[
              { id: "all", label: "Semua Hadiah" },
              { id: "voucher", label: "Voucher Tiket" },
              { id: "snack", label: "Snack & Minum" },
              { id: "seat", label: "Kursi VIP" },
              { id: "merchandise", label: "Merchandise" },
            ].map((cat) => (
              <TouchableOpacity
                key={cat.id}
                onPress={() => setSelectedCategory(cat.id as any)}
                style={[
                  styles.catChip,
                  selectedCategory === cat.id && styles.catChipActive,
                ]}
              >
                <Text
                  style={[
                    styles.catChipText,
                    selectedCategory === cat.id && styles.catChipTextActive,
                  ]}
                >
                  {cat.label}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>

          {/* Catalog Items */}
          <View style={styles.catalogGrid}>
            {filteredCatalog.map((item) => {
              const canRedeem = points >= item.pointsRequired;
              return (
                <View key={item.id} style={styles.catalogCard}>
                  <View style={styles.catalogCardLeft}>
                    <View style={styles.catalogIconBox}>
                      {item.category === "voucher" && (
                        <PromoVoucherIcon size={42} />
                      )}
                      {item.category === "snack" && (
                        <FreeMealBuffetIcon size={42} />
                      )}
                      {item.category === "seat" && (
                        <ExecutiveLeatherSeatsIcon size={42} />
                      )}
                      {item.category === "merchandise" && (
                        <RewardsLoyaltyIcon size={42} />
                      )}
                    </View>
                    <View style={{ flex: 1 }}>
                      <Text style={styles.catalogTitle}>{item.title}</Text>
                      <Text style={styles.catalogDesc}>{item.description}</Text>
                      <View style={styles.pointsCostRow}>
                        <Sparkles size={13} color="#D97706" />
                        <Text style={styles.pointsCostText}>
                          {item.pointsRequired.toLocaleString("id-ID")} Poin
                        </Text>
                      </View>
                    </View>
                  </View>

                  <TouchableOpacity
                    style={[
                      styles.redeemBtn,
                      !canRedeem && styles.redeemBtnDisabled,
                    ]}
                    onPress={() => setRedeemingItem(item)}
                    activeOpacity={0.85}
                  >
                    <Text
                      style={[
                        styles.redeemBtnText,
                        !canRedeem && styles.redeemBtnTextDisabled,
                      ]}
                    >
                      {canRedeem ? "Tukar" : "Poin Kurang"}
                    </Text>
                  </TouchableOpacity>
                </View>
              );
            })}
          </View>
        </View>

        {/* 4. CARA MUDAH KUMPULKAN POIN */}
        <View style={styles.sectionBox}>
          <Text style={styles.sectionTitle}>Cara Kumpulkan Poin</Text>
          <Text style={styles.sectionSub}>
            Raih poin sebanyak-banyaknya di setiap aktivitas Anda
          </Text>

          <View style={styles.howToGrid}>
            <View style={styles.howToCard}>
              <View
                style={[styles.howToIconBox, { backgroundColor: "#EFF6FF" }]}
              >
                <Ticket size={20} color="#2563EB" />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.howToTitle}>Beli Tiket Bus AKAP</Text>
                <Text style={styles.howToDesc}>
                  Dapatkan 10 Poin untuk setiap kelipatan Rp 10.000 pembelian
                  tiket.
                </Text>
              </View>
              <View style={styles.howToBadge}>
                <Text style={styles.howToBadgeText}>+100 Poin</Text>
              </View>
            </View>

            <View style={styles.howToCard}>
              <View
                style={[styles.howToIconBox, { backgroundColor: "#FEF3C7" }]}
              >
                <Zap size={20} color="#D97706" />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.howToTitle}>Check-In Streak Harian</Text>
                <Text style={styles.howToDesc}>
                  Buka aplikasi setiap hari untuk bonus poin tanpa henti.
                </Text>
              </View>
              <View style={styles.howToBadge}>
                <Text style={styles.howToBadgeText}>s.d +150 Poin</Text>
              </View>
            </View>

            <View style={styles.howToCard}>
              <View
                style={[styles.howToIconBox, { backgroundColor: "#ECFDF5" }]}
              >
                <Compass size={20} color="#059669" />
              </View>
              <View style={{ flex: 1 }}>
                <Text style={styles.howToTitle}>Sewa Bus Pariwisata</Text>
                <Text style={styles.howToDesc}>
                  Booking sewa carter rombongan dan nikmati cashback poin besar.
                </Text>
              </View>
              <View style={styles.howToBadge}>
                <Text style={styles.howToBadgeText}>+500 Poin</Text>
              </View>
            </View>
          </View>
        </View>

        {/* 5. TIER LOYALITAS INFO */}
        <View style={styles.sectionBox}>
          <Text style={styles.sectionTitle}>Tingkatan Level VIP</Text>
          <Text style={styles.sectionSub}>
            Nikmati fasilitas ekstra seiring bertambahnya status member Anda
          </Text>

          <View style={styles.tierCardsRow}>
            <View style={[styles.tierMiniCard, { borderColor: "#E2E8F0" }]}>
              <Text style={[styles.tierMiniTitle, { color: "#64748B" }]}>
                SILVER
              </Text>
              <Text style={styles.tierMiniPoints}>0 - 1.999 Poin</Text>
              <Text style={styles.tierMiniBenefit}>
                Diskon Reguler &amp; Servis Makan
              </Text>
            </View>

            <View
              style={[
                styles.tierMiniCard,
                { borderColor: "#FDE68A", backgroundColor: "#FFFBEB" },
              ]}
            >
              <Text style={[styles.tierMiniTitle, { color: "#D97706" }]}>
                GOLD VIP
              </Text>
              <Text style={styles.tierMiniPoints}>2.000 - 4.999 Poin</Text>
              <Text style={styles.tierMiniBenefit}>
                Prioritas Kursi &amp; Ekstra Poin 1.2x
              </Text>
            </View>

            <View
              style={[
                styles.tierMiniCard,
                { borderColor: "#DDD6FE", backgroundColor: "#FAF5FF" },
              ]}
            >
              <Text style={[styles.tierMiniTitle, { color: "#7C3AED" }]}>
                PLATINUM
              </Text>
              <Text style={styles.tierMiniPoints}>5.000+ Poin</Text>
              <Text style={styles.tierMiniBenefit}>
                Free Snack &amp; Bebas Reschedule
              </Text>
            </View>
          </View>
        </View>

        <View style={{ height: 40 }} />
      </ScrollView>

      {/* CONFIRMATION REDEEM MODAL */}
      <Modal
        visible={!!redeemingItem}
        transparent={true}
        animationType="fade"
        onRequestClose={() => setRedeemingItem(null)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalCard}>
            <View style={styles.modalIconSvgBox}>
              <RewardsLoyaltyIcon size={64} />
            </View>
            <Text style={styles.modalTitle}>Konfirmasi Tukar Poin</Text>
            <Text style={styles.modalSub}>
              Apakah Anda ingin menukarkan{" "}
              <Text style={{ fontFamily: "PlusJakartaSans_800ExtraBold" }}>
                {redeemingItem?.pointsRequired} Poin
              </Text>{" "}
              untuk hadiah{" "}
              <Text style={{ fontFamily: "PlusJakartaSans_800ExtraBold" }}>
                {redeemingItem?.title}
              </Text>
              ?
            </Text>

            <View style={styles.modalActionRow}>
              <TouchableOpacity
                style={styles.modalCancelBtn}
                onPress={() => setRedeemingItem(null)}
              >
                <Text style={styles.modalCancelText}>Batal</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={styles.modalConfirmBtn}
                onPress={handleConfirmRedeem}
              >
                <Text style={styles.modalConfirmText}>Ya, Tukar Sekarang</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

      {/* HISTORY MODAL */}
      <Modal
        visible={isHistoryModalOpen}
        transparent={true}
        animationType="slide"
        onRequestClose={() => setIsHistoryModalOpen(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={[styles.modalCard, { maxHeight: "80%", width: "100%" }]}>
            <View style={styles.historyModalHeader}>
              <Text style={styles.modalTitle}>Riwayat Poin Loyalitas</Text>
              <TouchableOpacity onPress={() => setIsHistoryModalOpen(false)}>
                <X size={20} color="#64748B" />
              </TouchableOpacity>
            </View>

            <ScrollView showsVerticalScrollIndicator={false}>
              {transactions.map((tx) => (
                <View key={tx.id} style={styles.txRow}>
                  <View style={styles.txLeft}>
                    <View
                      style={[
                        styles.txDot,
                        tx.type === "earn"
                          ? { backgroundColor: "#DCFCE7" }
                          : { backgroundColor: "#FEE2E2" },
                      ]}
                    >
                      <Sparkles
                        size={14}
                        color={tx.type === "earn" ? "#16A34A" : "#DC2626"}
                      />
                    </View>
                    <View>
                      <Text style={styles.txTitle}>{tx.title}</Text>
                      <Text style={styles.txDate}>{tx.date}</Text>
                    </View>
                  </View>
                  <Text
                    style={[
                      styles.txPoints,
                      tx.type === "earn"
                        ? { color: "#16A34A" }
                        : { color: "#DC2626" },
                    ]}
                  >
                    {tx.type === "earn" ? "+" : "-"}
                    {tx.points.toLocaleString("id-ID")} Poin
                  </Text>
                </View>
              ))}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  historyBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: "#BFDBFE",
  },
  historyBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  vipCard: {
    borderRadius: 22,
    padding: 20,
    marginBottom: 16,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.25,
        shadowRadius: 10,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  vipTopRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
  },
  tierBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.18)",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: "rgba(255, 255, 255, 0.3)",
  },
  tierBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: "#FFFFFF",
    letterSpacing: 0.5,
  },
  vipCardNumber: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "rgba(255, 255, 255, 0.8)",
  },
  pointsBalanceSection: {
    marginBottom: 16,
  },
  pointsLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "rgba(255, 255, 255, 0.85)",
    marginBottom: 2,
  },
  pointsValueRow: {
    flexDirection: "row",
    alignItems: "baseline",
    gap: 6,
  },
  pointsNumber: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 32,
    color: "#FFFFFF",
  },
  pointsUnitText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 16,
    color: "#FBBF24",
  },
  tierProgressContainer: {
    backgroundColor: "rgba(0, 0, 0, 0.2)",
    padding: 10,
    borderRadius: 12,
  },
  tierProgressLabels: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 6,
  },
  tierProgressText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#FFFFFF",
  },
  tierProgressPercent: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#FBBF24",
  },
  progressBarTrack: {
    height: 6,
    borderRadius: 3,
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    overflow: "hidden",
  },
  progressBarFill: {
    height: "100%",
    backgroundColor: "#FBBF24",
    borderRadius: 3,
  },
  sectionBox: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 16,
  },
  sectionHeaderRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 12,
    gap: 8,
  },
  sectionTitleCol: {
    flex: 1,
    paddingRight: 6,
  },
  sectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#0F172A",
  },
  sectionSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 2,
    lineHeight: 16,
  },
  streakCountBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FEF3C7",
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    flexShrink: 0,
  },
  streakCountText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#D97706",
  },
  streakScroll: {
    flexDirection: "row",
    gap: 8,
    paddingVertical: 6,
    marginBottom: 12,
  },
  streakDayCard: {
    width: 64,
    paddingVertical: 10,
    borderRadius: 14,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    alignItems: "center",
  },
  streakDayCardToday: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#EFF6FF",
  },
  streakDayCardPassed: {
    backgroundColor: "#F0FDF4",
    borderColor: "#BBF7D0",
  },
  streakDayTitle: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#64748B",
    marginBottom: 6,
  },
  streakDayTitleToday: {
    color: COLORS.brandBlue,
    fontFamily: "PlusJakartaSans_700Bold",
  },
  streakDayTitlePassed: {
    color: "#16A34A",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  streakSubLabelPassed: {
    color: "#16A34A",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  streakCoinCircle: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 4,
  },
  streakCoinCircleToday: {
    backgroundColor: COLORS.brandBlue,
  },
  streakCoinCirclePassed: {
    backgroundColor: "#16A34A",
  },
  streakCoinText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 10,
    color: "#475569",
  },
  streakCoinTextToday: {
    color: "#FFFFFF",
  },
  streakSubLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: "#94A3B8",
  },
  claimActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    paddingVertical: 12,
    borderRadius: 12,
  },
  claimActionBtnDisabled: {
    backgroundColor: "#94A3B8",
  },
  claimActionBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  categoryChipsRow: {
    flexDirection: "row",
    gap: 8,
    marginVertical: 12,
  },
  catChip: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  catChipActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  catChipText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: "#64748B",
  },
  catChipTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  catalogGrid: {
    gap: 10,
  },
  catalogCard: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    padding: 12,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    backgroundColor: "#F8FAFC",
  },
  catalogCardLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    flex: 1,
    marginRight: 10,
  },
  catalogIconBox: {
    width: 44,
    height: 44,
    borderRadius: 12,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
  },
  catalogTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#0F172A",
  },
  catalogDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    marginTop: 1,
  },
  pointsCostRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 4,
    marginTop: 4,
  },
  pointsCostText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11.5,
    color: "#D97706",
  },
  redeemBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 10,
  },
  redeemBtnDisabled: {
    backgroundColor: "#E2E8F0",
  },
  redeemBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
  redeemBtnTextDisabled: {
    color: "#94A3B8",
  },
  howToGrid: {
    gap: 10,
    marginTop: 10,
  },
  howToCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    gap: 12,
  },
  howToIconBox: {
    width: 42,
    height: 42,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
  },
  howToTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#0F172A",
    marginBottom: 2,
  },
  howToDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    lineHeight: 15,
  },
  howToBadge: {
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    borderRadius: 8,
    paddingHorizontal: 8,
    paddingVertical: 4,
  },
  howToBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: COLORS.brandBlue,
  },
  tierCardsRow: {
    flexDirection: "row",
    gap: 8,
    marginTop: 10,
  },
  tierMiniCard: {
    flex: 1,
    backgroundColor: "#FFFFFF",
    borderRadius: 12,
    borderWidth: 1.5,
    padding: 10,
    alignItems: "center",
  },
  tierMiniTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 10.5,
    letterSpacing: 0.5,
    marginBottom: 4,
  },
  tierMiniPoints: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#0F172A",
    marginBottom: 4,
    textAlign: "center",
  },
  tierMiniBenefit: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 13,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.65)",
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  modalCard: {
    width: "100%",
    maxWidth: 380,
    backgroundColor: "#FFFFFF",
    borderRadius: 24,
    padding: 20,
    alignItems: "center",
  },
  modalIconSvgBox: {
    width: 68,
    height: 68,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
  },
  modalIconCircle: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: "#EFF6FF",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
  },
  modalTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
    marginBottom: 6,
  },
  modalSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12.5,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 18,
    marginBottom: 18,
  },
  modalActionRow: {
    flexDirection: "row",
    gap: 10,
    width: "100%",
  },
  modalCancelBtn: {
    flex: 1,
    paddingVertical: 12,
    borderRadius: 12,
    backgroundColor: "#F1F5F9",
    alignItems: "center",
  },
  modalCancelText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#64748B",
  },
  modalConfirmBtn: {
    flex: 1.5,
    paddingVertical: 12,
    borderRadius: 12,
    backgroundColor: COLORS.brandBlue,
    alignItems: "center",
  },
  modalConfirmText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  historyModalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    width: "100%",
    marginBottom: 14,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  txRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
    width: "100%",
  },
  txLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    flex: 1,
  },
  txDot: {
    width: 32,
    height: 32,
    borderRadius: 16,
    justifyContent: "center",
    alignItems: "center",
  },
  txTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#0F172A",
  },
  txDate: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#94A3B8",
    marginTop: 1,
  },
  txPoints: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
  },
});
