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
import { formatIndonesianTime } from "../../utils/format";

interface HomePopularRoutesProps {
  schedules: any[];
  onNavigateSchedules: () => void;
  onSelectSchedule: (scheduleId: number | string) => void;
}

const DEFAULT_POPULAR_ROUTES = [
  {
    id: 1,
    route: { origin: "Kuningan", destination: "Jakarta (Kalideres)" },
    bus: { name: "Resi Bisma", bus_type: "Executive" },
    departure_time: "07:00:00",
    price: 140000,
  },
  {
    id: 2,
    route: { origin: "Kuningan", destination: "Jakarta (Roxy)" },
    bus: { name: "Primadona", bus_type: "Executive" },
    departure_time: "08:30:00",
    price: 140000,
  },
  {
    id: 3,
    route: { origin: "Kuningan", destination: "Banten (Bitung)" },
    bus: { name: "Bentas-01", bus_type: "Executive" },
    departure_time: "06:30:00",
    price: 150000,
  },
  {
    id: 4,
    route: { origin: "Kuningan", destination: "Jakarta (Pulogebang)" },
    bus: { name: "Kylo Ren", bus_type: "Super High Deck" },
    departure_time: "17:00:00",
    price: 140000,
  },
];

export const HomePopularRoutes: React.FC<HomePopularRoutesProps> = ({
  schedules,
  onNavigateSchedules,
  onSelectSchedule,
}) => {
  const displayList =
    schedules && schedules.length > 0 ? schedules : DEFAULT_POPULAR_ROUTES;

  return (
    <>
      <SectionHeader
        title="Rute Populer AKAP"
        actionLabel="Lihat Semua >"
        onAction={onNavigateSchedules}
      />

      <View style={styles.scheduleList}>
        {displayList.map((item, idx) => {
          const busName =
            item.bus?.name || (idx % 2 === 0 ? "Resi Bisma" : "Primadona");
          const origin = item.route?.origin || "Kuningan";
          const destination =
            item.route?.destination ||
            (idx % 2 === 0 ? "Jakarta (Kalideres)" : "Jakarta (Roxy)");
          const price = Number(item.price || 140000).toLocaleString("id-ID");
          const depTime = formatIndonesianTime(
            item.departure_time,
            idx === 0 ? "07:00" : "08:30",
          );

          const nameLower = (busName || "").toLowerCase();
          let thumbSource = require("../../../assets/images/resiBisma.webp");
          if (nameLower.includes("primadona"))
            thumbSource = require("../../../assets/images/primadona.webp");
          if (nameLower.includes("bentas"))
            thumbSource = require("../../../assets/images/bentas01.webp");
          if (nameLower.includes("kyloren"))
            thumbSource = require("../../../assets/images/kylorenParwis.webp");

          return (
            <TouchableOpacity
              key={item.id || idx}
              activeOpacity={0.85}
              onPress={() => onSelectSchedule(item.id || 1)}
              style={styles.scheduleCard}
            >
              <Image
                source={thumbSource}
                style={styles.scheduleBusThumb}
                resizeMode="cover"
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
    gap: 8,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    paddingHorizontal: 7,
    paddingVertical: 3,
    borderRadius: 6,
  },
  ratingText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
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
