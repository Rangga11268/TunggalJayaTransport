import React from "react";
import {
  View,
  Text,
  StyleSheet,
  Platform,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { Crown, Sparkles, Award } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface RewardHeroCardProps {
  tier: "Silver" | "Gold" | "Platinum" | "Gold VIP";
  points: number;
}

export const RewardHeroCard: React.FC<RewardHeroCardProps> = ({
  tier,
  points,
}) => {
  const getGradientColors = (): [string, string, ...string[]] => {
    if (tier === "Platinum") return ["#1E1B4B", "#4338CA", "#6366F1"];
    if (tier === "Gold VIP") return ["#78350F", "#B45309", "#F59E0B"];
    return [COLORS.brandBlue, "#1D4ED8", "#1E40AF"];
  };

  const nextTierPoints =
    tier === "Silver" ? 2000 : tier === "Gold VIP" ? 5000 : 10000;
  const progressPercent = Math.min(
    100,
    Math.round((points / nextTierPoints) * 100),
  );

  return (
    <LinearGradient
      colors={getGradientColors()}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 1 }}
      style={styles.vipCard}
    >
      <View style={styles.vipTopRow}>
        <View style={styles.tierBadge}>
          <Crown size={14} color="#FBBF24" style={{ marginRight: 5 }} />
          <Text style={styles.tierBadgeText}>
            MEMBER {tier.toUpperCase()}
          </Text>
        </View>
        <Text style={styles.vipCardNumber}>TJ-VIP-2026</Text>
      </View>

      <View style={styles.vipPointsCol}>
        <Text style={styles.vipPointsLabel}>SALDO TJ POINTS</Text>
        <View style={styles.vipPointsMainRow}>
          <Sparkles size={24} color="#FDE68A" style={{ marginRight: 6 }} />
          <Text style={styles.vipPointsValue}>
            {points.toLocaleString("id-ID")}
          </Text>
          <Text style={styles.vipPointsUnit}>Poin</Text>
        </View>
      </View>

      {/* Progress to Next Tier */}
      <View style={styles.tierProgressBox}>
        <View style={styles.tierProgressLabels}>
          <Text style={styles.tierProgressLabelText}>
            {tier === "Platinum"
              ? "Level Tertinggi Tercapai"
              : `Menuju ${tier === "Silver" ? "Gold VIP" : "Platinum"}`}
          </Text>
          <Text style={styles.tierProgressPercentText}>
            {points} / {nextTierPoints} Poin
          </Text>
        </View>
        <View style={styles.progressBarTrack}>
          <View
            style={[styles.progressBarFill, { width: `${progressPercent}%` }]}
          />
        </View>
      </View>
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
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
    color: "rgba(255, 255, 255, 0.75)",
  },
  vipPointsCol: {
    marginBottom: 16,
  },
  vipPointsLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "rgba(255, 255, 255, 0.75)",
    letterSpacing: 0.5,
  },
  vipPointsMainRow: {
    flexDirection: "row",
    alignItems: "baseline",
    marginTop: 2,
  },
  vipPointsValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 32,
    color: "#FFFFFF",
    letterSpacing: -0.5,
  },
  vipPointsUnit: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "rgba(255, 255, 255, 0.85)",
    marginLeft: 6,
  },
  tierProgressBox: {
    backgroundColor: "rgba(0, 0, 0, 0.18)",
    borderRadius: 12,
    padding: 10,
  },
  tierProgressLabels: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 6,
  },
  tierProgressLabelText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10.5,
    color: "#FFFFFF",
  },
  tierProgressPercentText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "#FDE68A",
  },
  progressBarTrack: {
    height: 6,
    borderRadius: 3,
    backgroundColor: "rgba(255, 255, 255, 0.25)",
    overflow: "hidden",
  },
  progressBarFill: {
    height: "100%",
    backgroundColor: "#FBBF24",
    borderRadius: 3,
  },
});
