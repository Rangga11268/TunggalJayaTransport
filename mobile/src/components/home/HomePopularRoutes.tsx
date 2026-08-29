import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Platform,
} from "react-native";
import { Star, ChevronRight } from "lucide-react-native";
import { SectionHeader } from "../SectionHeader";

interface HomePopularRoutesProps {
  schedules: any[];
  onNavigateSchedules: () => void;
  onSelectSchedule: (scheduleId: number | string) => void;
}

export const HomePopularRoutes: React.FC<HomePopularRoutesProps> = ({
  schedules,
  onNavigateSchedules,
  onSelectSchedule,
}) => {
  return (
    <>
      <SectionHeader
        title="Rute Populer AKAP"
        actionLabel="Lihat Semua >"
        onAction={onNavigateSchedules}
      />

      <View style={styles.scheduleList}>
        {schedules.map((item, idx) => {
          const busName =
            item.bus?.name || (idx % 2 === 0 ? "Resi Bisma" : "Primadona");
          const origin = item.route?.origin || "Kuningan";
          const destination =
            item.route?.destination ||
            (idx % 2 === 0 ? "Jakarta (Kalideres)" : "Jakarta (Roxy)");
          const price = Number(item.price || 140000).toLocaleString("id-ID");
          const depTime = item.departure_time
            ? item.departure_time.substring(11, 16) ||
              item.departure_time.substring(0, 5)
            : "07:45";

          return (
            <TouchableOpacity
              key={item.id || idx}
              activeOpacity={0.85}
              onPress={() => onSelectSchedule(item.id || 1)}
              style={styles.scheduleCard}
            >
              <Image
                source={
                  idx % 2 === 0
                    ? require("../../../assets/images/resiBisma.webp")
                    : require("../../../assets/images/primadona.webp")
                }
                style={styles.scheduleBusThumb}
              />
              <View style={styles.scheduleMiddle}>
                <Text style={styles.scheduleRoute}>
                  {origin} → {destination}
                </Text>
                <Text style={styles.scheduleBusName}>
                  {busName} • {depTime} WIB
                </Text>
                <Text style={styles.schedulePrice}>
                  Rp {price} <Text style={styles.schedulePerPerson}>/ org</Text>
                </Text>
              </View>
              <View style={styles.scheduleAction}>
                <View style={styles.ratingBadge}>
                  <Star
                    size={11}
                    color="#D97706"
                    fill="#D97706"
                    style={{ marginRight: 3 }}
                  />
                  <Text style={styles.ratingText}>4.9</Text>
                </View>
                <View style={styles.chevronCircle}>
                  <ChevronRight size={16} color="#FFFFFF" />
                </View>
              </View>
            </TouchableOpacity>
          );
        })}
      </View>
    </>
  );
};

const styles = StyleSheet.create({
  scheduleList: {
    gap: 12,
    marginBottom: 24,
  },
  scheduleCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 12,
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
      web: {
        boxShadow: "0 2px 8px rgba(15, 23, 42, 0.04)",
      } as any,
    }),
  },
  scheduleBusThumb: {
    width: 68,
    height: 68,
    borderRadius: 12,
  },
  scheduleMiddle: {
    flex: 1,
    marginLeft: 12,
  },
  scheduleRoute: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  scheduleBusName: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    marginTop: 2,
  },
  schedulePrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#2563EB",
    marginTop: 4,
  },
  schedulePerPerson: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#94A3B8",
  },
  scheduleAction: {
    alignItems: "flex-end",
    gap: 10,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    paddingHorizontal: 7,
    paddingVertical: 3,
    borderRadius: 8,
  },
  ratingText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#D97706",
  },
  chevronCircle: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: "#2563EB",
    justifyContent: "center",
    alignItems: "center",
  },
});
