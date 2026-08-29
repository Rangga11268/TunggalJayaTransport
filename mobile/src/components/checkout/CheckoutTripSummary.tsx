import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { COLORS } from "../../theme/colors";
import { formatIndonesianDate } from "../../utils/format";

interface CheckoutTripSummaryProps {
  schedule: any;
  selectedSeats: (number | string)[];
  date?: string;
}

export const CheckoutTripSummary: React.FC<CheckoutTripSummaryProps> = ({
  schedule,
  selectedSeats,
  date,
}) => {
  return (
    <View style={styles.card}>
      <Text style={styles.cardSectionTitle}>Rincian Perjalanan</Text>
      <View style={styles.tripRouteRow}>
        <Text style={styles.tripRouteText}>
          {schedule?.route?.origin || "Jakarta"} →{" "}
          {schedule?.route?.destination || "Kuningan"}
          {schedule?.route?.origin || "Kuningan"} →{" "}
          {schedule?.route?.destination || "Jakarta"}
        </Text>
        <View style={styles.classBadge}>
          <Text style={styles.classBadgeText}>Bus Reguler</Text>
          <Text style={styles.classBadgeText}>
            {schedule?.bus?.bus_type ||
              schedule?.bus?.type ||
              "Executive Class"}
          </Text>
        </View>
      </View>
      <Text style={styles.busInfoText}>
        {schedule?.bus?.name || "Resi Bisma"} •{" "}
        {date ? formatIndonesianDate(date, false) : "Hari Ini"}
      </Text>
      <View style={styles.seatBadgeRow}>
        <Text style={styles.seatBadgeLabel}>Kursi Terpilih:</Text>
        <View style={styles.seatBadgePill}>
          <Text style={styles.seatBadgePillText}>
            No. {selectedSeats?.join(", ")} ({selectedSeats?.length} Kursi)
          </Text>
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  card: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    marginBottom: 16,
  },
  cardSectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
    marginBottom: 12,
  },
  tripRouteRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  tripRouteText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
  },
  classBadge: {
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  classBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: COLORS.brandBlue,
  },
  busInfoText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#6B7280",
    marginTop: 4,
  },
  seatBadgeRow: {
    flexDirection: "row",
    alignItems: "center",
    marginTop: 12,
    gap: 8,
  },
  seatBadgeLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#6B7280",
  },
  seatBadgePill: {
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: "#BFDBFE",
  },
  seatBadgePillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
});
