import React, { createContext, useContext, useState, useEffect } from "react";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Alert, Platform } from "react-native";

export interface RewardItem {
  id: string;
  title: string;
  category: "voucher" | "snack" | "merchandise" | "seat";
  pointsRequired: number;
  description: string;
  voucherCode?: string;
  expiryDate?: string;
  image?: any;
}

export interface PointTransaction {
  id: string;
  type: "earn" | "redeem";
  title: string;
  points: number;
  date: string;
  description: string;
}

interface RewardContextType {
  points: number;
  tier: "Silver" | "Gold" | "Platinum";
  streakDays: number;
  hasClaimedToday: boolean;
  transactions: PointTransaction[];
  redeemedVouchers: string[];
  claimDailyCheckIn: () => Promise<boolean>;
  earnPointsFromBooking: (ticketAmount: number) => Promise<void>;
  redeemReward: (item: RewardItem) => Promise<boolean>;
}

const RewardContext = createContext<RewardContextType | undefined>(undefined);

const DAILY_REWARDS = [10, 20, 30, 40, 50, 75, 150];

export const RewardProvider: React.FC<{ children: React.ReactNode }> = ({
  children,
}) => {
  const [points, setPoints] = useState<number>(1250);
  const [streakDays, setStreakDays] = useState<number>(3);
  const [lastClaimDate, setLastClaimDate] = useState<string>("");
  const [transactions, setTransactions] = useState<PointTransaction[]>([
    {
      id: "tx-1",
      type: "earn",
      title: "Bonus Registrasi Member",
      points: 500,
      date: "2026-08-20",
      description: "Pendaftaran akun resmi PO Tunggal Jaya Mobile",
    },
    {
      id: "tx-2",
      type: "earn",
      title: "Cashback Tiket Kuningan - Jakarta",
      points: 450,
      date: "2026-08-27",
      description: "Pemesanan armada Resi Bisma (#TJ-BK9043)",
    },
    {
      id: "tx-3",
      type: "earn",
      title: "Check-In Harian",
      points: 300,
      date: "2026-08-28",
      description: "Streak check-in hari ke-3",
    },
  ]);
  const [redeemedVouchers, setRedeemedVouchers] = useState<string[]>([]);

  useEffect(() => {
    loadRewardsData();
  }, []);

  const loadRewardsData = async () => {
    try {
      const savedPoints = await AsyncStorage.getItem("@tj_reward_points");
      const savedStreak = await AsyncStorage.getItem("@tj_reward_streak");
      const savedLastClaim = await AsyncStorage.getItem(
        "@tj_reward_last_claim",
      );
      const savedTxs = await AsyncStorage.getItem("@tj_reward_txs");
      const savedVouchers = await AsyncStorage.getItem("@tj_reward_vouchers");

      if (savedPoints !== null) setPoints(parseInt(savedPoints, 10));
      if (savedStreak !== null) setStreakDays(parseInt(savedStreak, 10));
      if (savedLastClaim !== null) setLastClaimDate(savedLastClaim);
      if (savedTxs !== null) setTransactions(JSON.parse(savedTxs));
      if (savedVouchers !== null)
        setRedeemedVouchers(JSON.parse(savedVouchers));
    } catch (e) {
      console.log("Error loading rewards:", e);
    }
  };

  const saveRewardsData = async (
    newPoints: number,
    newStreak: number,
    newClaimDate: string,
    newTxs: PointTransaction[],
    newVouchers: string[],
  ) => {
    try {
      await AsyncStorage.setItem("@tj_reward_points", newPoints.toString());
      await AsyncStorage.setItem("@tj_reward_streak", newStreak.toString());
      await AsyncStorage.setItem("@tj_reward_last_claim", newClaimDate);
      await AsyncStorage.setItem("@tj_reward_txs", JSON.stringify(newTxs));
      await AsyncStorage.setItem(
        "@tj_reward_vouchers",
        JSON.stringify(newVouchers),
      );
    } catch (e) {
      console.log("Error saving rewards:", e);
    }
  };

  const todayStr = new Date().toISOString().split("T")[0];
  const hasClaimedToday = lastClaimDate === todayStr;

  const getTier = (): "Silver" | "Gold" | "Platinum" => {
    if (points >= 5000) return "Platinum";
    if (points >= 1000) return "Gold";
    return "Silver";
  };

  const claimDailyCheckIn = async (): Promise<boolean> => {
    if (hasClaimedToday) {
      return false;
    }

    const currentDayIndex = streakDays % 7;
    const bonus = DAILY_REWARDS[currentDayIndex] || 20;
    const nextPoints = points + bonus;
    const nextStreak = streakDays + 1;

    const newTx: PointTransaction = {
      id: `tx-checkin-${Date.now()}`,
      type: "earn",
      title: `Check-In Harian (Hari ke-${currentDayIndex + 1})`,
      points: bonus,
      date: todayStr,
      description: "Bonus streak login aplikasi mobile",
    };

    const nextTxs = [newTx, ...transactions];

    setPoints(nextPoints);
    setStreakDays(nextStreak);
    setLastClaimDate(todayStr);
    setTransactions(nextTxs);

    await saveRewardsData(
      nextPoints,
      nextStreak,
      todayStr,
      nextTxs,
      redeemedVouchers,
    );

    return true;
  };

  const earnPointsFromBooking = async (ticketAmount: number) => {
    const earned = Math.max(50, Math.floor(ticketAmount * 0.01)); // 1% points
    const nextPoints = points + earned;

    const newTx: PointTransaction = {
      id: `tx-booking-${Date.now()}`,
      type: "earn",
      title: "Cashback Poin Pemesanan Tiket",
      points: earned,
      date: todayStr,
      description: "Perolehan poin dari transaksi tiket bus",
    };

    const nextTxs = [newTx, ...transactions];
    setPoints(nextPoints);
    setTransactions(nextTxs);

    await saveRewardsData(
      nextPoints,
      streakDays,
      lastClaimDate,
      nextTxs,
      redeemedVouchers,
    );
  };

  const redeemReward = async (item: RewardItem): Promise<boolean> => {
    if (points < item.pointsRequired) {
      return false;
    }

    const nextPoints = points - item.pointsRequired;
    const voucherCode =
      item.voucherCode ||
      `TJ-RW-${Math.random().toString(36).substring(2, 7).toUpperCase()}`;

    const newTx: PointTransaction = {
      id: `tx-redeem-${Date.now()}`,
      type: "redeem",
      title: `Tukar: ${item.title}`,
      points: item.pointsRequired,
      date: todayStr,
      description: `Kode voucher: ${voucherCode}`,
    };

    const nextTxs = [newTx, ...transactions];
    const nextVouchers = [...redeemedVouchers, voucherCode];

    setPoints(nextPoints);
    setTransactions(nextTxs);
    setRedeemedVouchers(nextVouchers);

    await saveRewardsData(
      nextPoints,
      streakDays,
      lastClaimDate,
      nextTxs,
      nextVouchers,
    );

    return true;
  };

  return (
    <RewardContext.Provider
      value={{
        points,
        tier: getTier(),
        streakDays,
        hasClaimedToday,
        transactions,
        redeemedVouchers,
        claimDailyCheckIn,
        earnPointsFromBooking,
        redeemReward,
      }}
    >
      {children}
    </RewardContext.Provider>
  );
};

export const useRewards = () => {
  const context = useContext(RewardContext);
  if (!context) {
    throw new Error("useRewards must be used within a RewardProvider");
  }
  return context;
};
