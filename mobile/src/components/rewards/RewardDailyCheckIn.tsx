import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet,
} from "react-native";
import { Sparkles, Check, Flame } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface RewardDailyCheckInProps {
  streakDays: number;
  hasClaimedToday: boolean;
  claiming: boolean;
  onClaim: () => void;
}

export const RewardDailyCheckIn: React.FC<RewardDailyCheckInProps> = ({
  streakDays,
  hasClaimedToday,
  claiming,
  onClaim,
}) => {
  const STREAK_BONUSES = [10, 20, 30, 40, 50, 75, 150];

  return (
    <View style={styles.streakCard}>
      <View style={styles.streakTopRow}>
        <View style={{ flex: 1 }}>
          <View style={styles.streakTitleRow}>
            <Flame size={16} color="#DC2626" style={{ marginRight: 4 }} />
            <Text style={styles.streakTitle}>Check-In Harian Streak</Text>
          </View>
          <Text style={styles.streakSub}>
            Check-in tiap hari untuk bonus poin makin berlipat!
          </Text>
        </View>
        <View style={styles.streakCountBadge}>
          <Text style={styles.streakCountNumber}>{streakDays}</Text>
          <Text style={styles.streakCountLabel}>Hari</Text>
        </View>
      </View>

      {/* 7 Days Streak Row */}
      <View style={styles.streakBoxesRow}>
        {STREAK_BONUSES.map((pts, idx) => {
          const dayNum = idx + 1;
          const isDone = dayNum <= streakDays;
          const isCurrentToday = dayNum === streakDays + 1 && !hasClaimedToday;

          return (
            <View
              key={idx}
              style={[
                styles.streakBox,
                isDone && styles.streakBoxDone,
                isCurrentToday && styles.streakBoxCurrent,
              ]}
            >
              <Text
                style={[
                  styles.streakBoxDayText,
                  isDone && styles.streakBoxDayTextDone,
                  isCurrentToday && styles.streakBoxDayTextCurrent,
                ]}
              >
                H-{dayNum}
              </Text>
              <View style={styles.streakBoxIconBox}>
                {isDone ? (
                  <Check size={14} color="#16A34A" />
                ) : (
                  <Sparkles
                    size={13}
                    color={isCurrentToday ? "#D97706" : "#9CA3AF"}
                  />
                )}
              </View>
              <Text
                style={[
                  styles.streakBoxPointsText,
                  isDone && styles.streakBoxPointsTextDone,
                  isCurrentToday && styles.streakBoxPointsTextCurrent,
                ]}
              >
                +{pts}
              </Text>
            </View>
          );
        })}
      </View>

      {/* Claim Button */}
      <TouchableOpacity
        style={[
          styles.claimButton,
          hasClaimedToday && styles.claimButtonDisabled,
        ]}
        onPress={onClaim}
        disabled={hasClaimedToday || claiming}
        activeOpacity={0.85}
      >
        {claiming ? (
          <ActivityIndicator color="#FFFFFF" />
        ) : (
          <Text style={styles.claimButtonText}>
            {hasClaimedToday
              ? "✓ Sudah Check-In Hari Ini"
              : "Klaim Bonus Poin Hari Ini"}
          </Text>
        )}
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  streakCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    marginBottom: 20,
  },
  streakTopRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 14,
  },
  streakTitleRow: {
    flexDirection: "row",
    alignItems: "center",
  },
  streakTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#111827",
  },
  streakSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  streakCountBadge: {
    backgroundColor: "#FEF2F2",
    borderWidth: 1,
    borderColor: "#FECACA",
    borderRadius: 12,
    paddingHorizontal: 10,
    paddingVertical: 4,
    alignItems: "center",
  },
  streakCountNumber: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#DC2626",
    lineHeight: 16,
  },
  streakCountLabel: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 8.5,
    color: "#EF4444",
  },
  streakBoxesRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 14,
    gap: 4,
  },
  streakBox: {
    flex: 1,
    backgroundColor: "#F9FAFB",
    borderRadius: 10,
    paddingVertical: 8,
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E5E7EB",
  },
  streakBoxDone: {
    backgroundColor: "#F0FDF4",
    borderColor: "#BBF7D0",
  },
  streakBoxCurrent: {
    backgroundColor: "#FFFBEB",
    borderColor: "#FDE68A",
  },
  streakBoxDayText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9,
    color: "#9CA3AF",
    marginBottom: 4,
  },
  streakBoxDayTextDone: {
    color: "#16A34A",
  },
  streakBoxDayTextCurrent: {
    color: "#D97706",
  },
  streakBoxIconBox: {
    marginVertical: 2,
  },
  streakBoxPointsText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9.5,
    color: "#6B7280",
    marginTop: 2,
  },
  streakBoxPointsTextDone: {
    color: "#16A34A",
  },
  streakBoxPointsTextCurrent: {
    color: "#D97706",
  },
  claimButton: {
    backgroundColor: COLORS.brandBlue,
    borderRadius: 12,
    paddingVertical: 12,
    alignItems: "center",
    justifyContent: "center",
  },
  claimButtonDisabled: {
    backgroundColor: "#E5E7EB",
  },
  claimButtonText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
});
