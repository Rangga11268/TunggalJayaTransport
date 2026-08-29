import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
} from "react-native";
import { History } from "lucide-react-native";
import { COLORS } from "../theme/colors";
import { ScreenHeader } from "../components/ScreenHeader";
import { useRewards, RewardItem } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";

// Modular Rewards Components
import { RewardHeroCard } from "../components/rewards/RewardHeroCard";
import { RewardDailyCheckIn } from "../components/rewards/RewardDailyCheckIn";
import { RewardCatalogSection } from "../components/rewards/RewardCatalogSection";
import { RewardHowToEarn } from "../components/rewards/RewardHowToEarn";
import { RewardModals } from "../components/rewards/RewardModals";

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
  const { showSuccess, showError, showInfo, showAlert } = useCustomAlert();

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
      const earned = await claimDailyCheckIn();
      showSuccess(
        "Check-In Berhasil!",
        `Selamat! Anda memperoleh +${earned} TJ Points hari ini. Pertahankan streak check-in Anda untuk bonus lebih tinggi!`,
      );
    } catch (err: any) {
      showError("Gagal Check-In", err?.message || "Terjadi kesalahan.");
    } finally {
      setClaiming(false);
    }
  };

  const handleConfirmRedeem = async () => {
    if (!redeemingItem) return;
    const item = redeemingItem;
    setRedeemingItem(null);

    const success = await redeemReward(item);
    if (success) {
      showAlert({
        title: "Penukaran Berhasil! 🎉",
        message: `Hadiah "${item.title}" telah ditambahkan ke akun Anda.\n\nKode Voucher: ${item.voucherCode || "TJ-REWARD"}\n\nGunakan kode ini pada saat checkout tiket atau tunjukkan ke kru bus.`,
        type: "success",
        buttons: [
          {
            text: "Salin Kode",
            style: "cancel",
            onPress: () => {
              showSuccess("Kode Disalin", "Kode voucher siap digunakan.");
            },
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
        <RewardHeroCard tier={tier} points={points} />

        {/* 2. DAILY CHECK-IN STREAK SECTION */}
        <RewardDailyCheckIn
          streakDays={streakDays}
          hasClaimedToday={hasClaimedToday}
          claiming={claiming}
          onClaim={handleClaim}
        />

        {/* 3. REDEEM REWARDS CATALOG */}
        <RewardCatalogSection
          selectedCategory={selectedCategory}
          filteredCatalog={filteredCatalog}
          points={points}
          onSelectCategory={setSelectedCategory}
          onRedeemItem={setRedeemingItem}
        />

        {/* 4. HOW TO EARN & VIP TIERS */}
        <RewardHowToEarn />

        <View style={{ height: 40 }} />
      </ScrollView>

      {/* MODALS */}
      <RewardModals
        redeemingItem={redeemingItem}
        isHistoryModalOpen={isHistoryModalOpen}
        transactions={transactions}
        onCloseRedeem={() => setRedeemingItem(null)}
        onConfirmRedeem={handleConfirmRedeem}
        onCloseHistory={() => setIsHistoryModalOpen(false)}
      />
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
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
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
});
