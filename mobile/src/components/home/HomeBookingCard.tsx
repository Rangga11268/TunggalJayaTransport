import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Platform,
} from "react-native";
import {
  Sparkles,
  ArrowLeftRight,
  Calendar,
  Bus,
} from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface HomeBookingCardProps {
  user: any;
  originCity: string;
  destinationCity: string;
  selectedDayTab: "today" | "tomorrow";
  getGreeting: () => string;
  onOpenRewards: () => void;
  onSetOriginCity: (city: string) => void;
  onSetDestinationCity: (city: string) => void;
  onSwapCities: () => void;
  onSelectDayTab: (tab: "today" | "tomorrow") => void;
  onSearchSchedules: () => void;
}

export const HomeBookingCard: React.FC<HomeBookingCardProps> = ({
  user,
  originCity,
  destinationCity,
  selectedDayTab,
  getGreeting,
  onOpenRewards,
  onSetOriginCity,
  onSetDestinationCity,
  onSwapCities,
  onSelectDayTab,
  onSearchSchedules,
}) => {
  return (
    <>
      {/* User Greeting & VIP Status Banner */}
      <View style={styles.greetingSection}>
        <View style={styles.greetingHeaderRow}>
          <View style={{ flex: 1 }}>
            <Text style={styles.greetingTimeText}>{getGreeting()},</Text>
            <Text style={styles.greetingNameText} numberOfLines={1}>
              {user?.name || "Sobat Tunggal Jaya"}
            </Text>
          </View>
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={onOpenRewards}
            style={styles.vipPillBadge}
          >
            <Sparkles size={12} color="#D97706" style={{ marginRight: 4 }} />
            <Text style={styles.vipPillText}>TJ Rewards &gt;</Text>
          </TouchableOpacity>
        </View>
        <Text style={styles.greetingSubtitle}>
          Mau bepergian nyaman kemana hari ini?
        </Text>
      </View>

      {/* 1. INTERACTIVE TRIP SEARCH CARD */}
      <View style={styles.searchCard}>
        <View style={styles.routeSelectorRow}>
          {/* Origin */}
          <TouchableOpacity
            activeOpacity={0.7}
            style={styles.routeCityBox}
            onPress={() =>
              onSetOriginCity(originCity === "Kuningan" ? "Cirebon" : "Kuningan")
            }
          >
            <Text style={styles.routeBoxLabel}>DARI</Text>
            <Text style={styles.routeBoxCity}>{originCity}</Text>
            <Text style={styles.routeBoxPool}>Pool Cirendang / Terminal</Text>
          </TouchableOpacity>

          {/* Swap Button */}
          <TouchableOpacity
            activeOpacity={0.75}
            onPress={onSwapCities}
            style={styles.swapCityBtn}
          >
            <ArrowLeftRight size={16} color={COLORS.brandBlue} />
          </TouchableOpacity>

          {/* Destination */}
          <TouchableOpacity
            activeOpacity={0.7}
            style={[styles.routeCityBox, { alignItems: "flex-end" }]}
            onPress={() =>
              onSetDestinationCity(
                destinationCity === "Jakarta" ? "Banten (Bitung)" : "Jakarta",
              )
            }
          >
            <Text style={styles.routeBoxLabel}>TUJUAN</Text>
            <Text style={styles.routeBoxCity}>{destinationCity}</Text>
            <Text style={styles.routeBoxPool}>Kalideres / Roxy / Bitung</Text>
          </TouchableOpacity>
        </View>

        {/* Quick Date Selector Strip */}
        <View style={styles.searchDateRow}>
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => onSelectDayTab("today")}
            style={[
              styles.searchDatePill,
              selectedDayTab === "today" && styles.searchDatePillActive,
            ]}
          >
            <Calendar
              size={13}
              color={selectedDayTab === "today" ? "#FFFFFF" : "#6B7280"}
              style={{ marginRight: 5 }}
            />
            <Text
              style={[
                styles.searchDateText,
                selectedDayTab === "today" && styles.searchDateTextActive,
              ]}
            >
              Hari Ini
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => onSelectDayTab("tomorrow")}
            style={[
              styles.searchDatePill,
              selectedDayTab === "tomorrow" && styles.searchDatePillActive,
            ]}
          >
            <Calendar
              size={13}
              color={selectedDayTab === "tomorrow" ? "#FFFFFF" : "#6B7280"}
              style={{ marginRight: 5 }}
            />
            <Text
              style={[
                styles.searchDateText,
                selectedDayTab === "tomorrow" && styles.searchDateTextActive,
              ]}
            >
              Besok
            </Text>
          </TouchableOpacity>

          <View style={styles.tollBadge}>
            <Text style={styles.tollBadgeText}>Tol Cipali PP</Text>
          </View>
        </View>

        {/* Search CTA Button */}
        <TouchableOpacity
          activeOpacity={0.85}
          onPress={onSearchSchedules}
          style={styles.searchActionBtn}
        >
          <Bus size={17} color="#FFFFFF" style={{ marginRight: 8 }} />
          <Text style={styles.searchActionBtnText}>
            Cari Jadwal &amp; Kursi Bus
          </Text>
        </TouchableOpacity>
      </View>
    </>
  );
};

const styles = StyleSheet.create({
  greetingSection: {
    marginBottom: 14,
  },
  greetingHeaderRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
  },
  greetingTimeText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12.5,
    color: "#64748B",
  },
  greetingNameText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: "#0F172A",
    letterSpacing: -0.3,
  },
  greetingSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
    marginTop: 2,
  },
  vipPillBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    borderWidth: 1,
    borderColor: "#FDE68A",
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 20,
  },
  vipPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#D97706",
  },
  searchCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.08,
        shadowRadius: 16,
      },
      android: {
        elevation: 4,
      },
      web: {
        boxShadow: "0 6px 18px rgba(15, 23, 42, 0.06)",
      } as any,
    }),
  },
  routeSelectorRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 16,
    paddingHorizontal: 14,
    paddingVertical: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  routeCityBox: {
    flex: 1,
  },
  routeBoxLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#94A3B8",
    letterSpacing: 0.5,
  },
  routeBoxCity: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#0F172A",
    marginVertical: 2,
  },
  routeBoxPool: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
  },
  swapCityBtn: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#CBD5E1",
    justifyContent: "center",
    alignItems: "center",
    marginHorizontal: 8,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.08,
        shadowRadius: 4,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  searchDateRow: {
    flexDirection: "row",
    alignItems: "center",
    marginTop: 12,
    gap: 8,
  },
  searchDatePill: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F1F5F9",
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: 10,
  },
  searchDatePillActive: {
    backgroundColor: COLORS.brandBlue,
  },
  searchDateText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: "#64748B",
  },
  searchDateTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  tollBadge: {
    marginLeft: "auto",
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  tollBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: COLORS.brandBlue,
  },
  searchActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    borderRadius: 14,
    paddingVertical: 13,
    marginTop: 14,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.25,
        shadowRadius: 8,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  searchActionBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
});
