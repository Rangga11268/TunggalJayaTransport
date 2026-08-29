import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Platform,
} from "react-native";
import {
  Star,
  Bus,
  Navigation,
  Calendar,
  ArrowRight,
  ChevronRight,
} from "lucide-react-native";
import { COLORS } from "../../theme/colors";
import { formatIndonesianTime } from "../../utils/format";
import { DateOption } from "./ScheduleDatePicker";

interface ScheduleCardProps {
  item: any;
  originCity: string;
  destCity: string;
  isDeparted: boolean;
  tomorrowOption: DateOption;
  stops: string[];
  onSelectTomorrow: (dateStr: string, busName: string, depTime: string) => void;
  onBookSchedule: (scheduleId: number) => void;
}

export const ScheduleCard: React.FC<ScheduleCardProps> = ({
  item,
  originCity,
  destCity,
  isDeparted,
  tomorrowOption,
  stops,
  onSelectTomorrow,
  onBookSchedule,
}) => {
  const busName = item.bus?.name || "Resi Bisma";
  const busType = item.bus?.bus_type || item.bus?.type || "Executive Class";
  const capacity = item.bus?.capacity || 50;
  const availableSeats = item.available_seats;
  const origin = item.route?.origin || originCity;
  const destination = item.route?.destination || destCity;
  const depTime = formatIndonesianTime(item.departure_time, "07:00");
  const arrTime = formatIndonesianTime(item.arrival_time, "11:00");
  const price = Number(item.price || 140000).toLocaleString("id-ID");
  const duration = item.duration || "6 Jam";
  const nextDepartureText =
    item.next_departure_formatted ||
    `Besok (${tomorrowOption.dayNum} ${tomorrowOption.monthName}) • ${depTime} WIB`;

  const nameLower = (busName || "").toLowerCase();
  let thumbSource = require("../../../assets/images/resiBisma.webp");
  if (nameLower.includes("primadona"))
    thumbSource = require("../../../assets/images/primadona.webp");
  if (nameLower.includes("bentas"))
    thumbSource = require("../../../assets/images/bentas01.webp");
  if (nameLower.includes("kyloren"))
    thumbSource = require("../../../assets/images/kylorenParwis.webp");

  return (
    <View style={[styles.ticketCard, isDeparted && styles.ticketCardDeparted]}>
      {/* Header Bus Row */}
      <View style={styles.cardHeader}>
        <View style={styles.busInfoLeft}>
          <Image
            source={thumbSource}
            style={[styles.busAvatar, isDeparted && { opacity: 0.55 }]}
            style={[styles.busAvatar, isDeparted && { opacity: 0.7 }]}
            resizeMode="cover"
          />
          <View>
          <View style={{ flex: 1 }}>
            <Text
              style={[styles.busNameText, isDeparted && { color: "#6B7280" }]}
              style={[styles.busNameText, isDeparted && { color: "#374151" }]}
              numberOfLines={1}
            >
              {busName}
            </Text>
            <Text style={styles.busClassText}>
            <Text style={styles.busClassText} numberOfLines={1}>
              {busType} • {capacity} Kursi (2-2)
              {availableSeats !== undefined ? ` • Sisa ${availableSeats}` : ""}
            </Text>
          </View>
        </View>

        {isDeparted ? (
          <View style={styles.departedBadge}>
            <Text style={styles.departedBadgeText}>BERANGKAT</Text>
            <Text style={styles.departedBadgeText}>SUDAH BERANGKAT</Text>
          </View>
        ) : (
          <View style={styles.ratingBadge}>
            <Star
              size={10}
              color="#D97706"
              fill="#D97706"
              style={{ marginRight: 3 }}
            />
            <Text style={styles.ratingBadgeText}>4.9</Text>
          </View>
        )}
      </View>

      {/* Main Departure Timeline Flow */}
      <View style={styles.timelineContainer}>
      <View
        style={[
          styles.timelineContainer,
          isDeparted && styles.timelineContainerDeparted,
        ]}
      >
        {/* Left: Departure */}
        <View style={styles.timeCol}>
          <Text
            style={[styles.bigTimeText, isDeparted && { color: "#6B7280" }]}
            style={[styles.bigTimeText, isDeparted && { color: "#4B5563" }]}
          >
            {depTime}
          </Text>
          <Text style={styles.terminalNameText} numberOfLines={1}>
            {origin}
          </Text>
        </View>

        {/* Middle: Duration & Route Line */}
        <View style={styles.lineCol}>
          <Text style={styles.durationText}>8j 00m</Text>
          <Text style={styles.durationText}>{duration}</Text>
          <View style={styles.dashedLineRow}>
            <View style={styles.lineDot} />
            <View style={styles.lineBar} />
            <Bus
              size={13}
              color={isDeparted ? "#9CA3AF" : COLORS.brandBlue}
              style={{ marginHorizontal: 4 }}
            />
            <View style={styles.lineBar} />
            <View style={styles.lineDot} />
          </View>
          <Text style={styles.transitText}>Via Tol Cipali</Text>
        </View>

        {/* Right: Arrival */}
        <View style={[styles.timeCol, { alignItems: "flex-end" }]}>
          <Text
            style={[styles.bigTimeText, isDeparted && { color: "#6B7280" }]}
            style={[styles.bigTimeText, isDeparted && { color: "#4B5563" }]}
          >
            {arrTime}
          </Text>
          <Text
            style={[styles.terminalNameText, { textAlign: "right" }]}
            numberOfLines={1}
          >
            {destination}
          </Text>
        </View>
      </View>

      {/* Intermediate Stops Strip */}
      <View style={styles.stopsBox}>
        <Navigation size={11} color="#6B7280" style={{ marginRight: 4 }} />
        <Navigation size={11} color="#475569" style={{ marginRight: 5 }} />
        <Text style={styles.stopsBoxText} numberOfLines={1}>
          Lintas: {stops.join(" • ")}
        </Text>
      </View>

      {/* Next Departure Banner if Departed Today */}
      {isDeparted ? (
      {isDeparted && (
        <View style={styles.nextDepartureCard}>
          <Calendar size={13} color="#4B5563" style={{ marginRight: 6 }} />
          <Text style={styles.nextDepartureCardText}>
          <Calendar size={13} color="#B45309" style={{ marginRight: 6 }} />
          <Text style={styles.nextDepartureCardText} numberOfLines={1}>
            Trip Hari Ini Selesai •{" "}
            <Text style={styles.nextDepartureCardBold}>
              Trip Berikutnya: Besok ({tomorrowOption.dayNum}{" "}
              {tomorrowOption.monthName}) {depTime} WIB
              {nextDepartureText}
            </Text>
          </Text>
        </View>
      ) : null}
      )}

      {/* Footer: Seat info & Action CTA */}
      <View style={styles.cardFooter}>
        <View>
          <Text style={styles.fareLabel}>Harga per orang</Text>
          <Text style={[styles.fareValue, isDeparted && { color: "#6B7280" }]}>
          <Text style={[styles.fareValue, isDeparted && { color: "#4B5563" }]}>
            Rp {price}
          </Text>
        </View>

        {isDeparted ? (
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() =>
              onSelectTomorrow(tomorrowOption.dateStr, busName, depTime)
              onSelectTomorrow(
                item.next_departure_date || tomorrowOption.dateStr,
                busName,
                depTime,
              )
            }
            style={styles.tomorrowActionBtn}
          >
            <Text style={styles.tomorrowActionText}>Pesan Besok</Text>
            <ArrowRight size={13} color="#FFFFFF" style={{ marginLeft: 4 }} />
          </TouchableOpacity>
        ) : (
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() => onBookSchedule(item.id)}
            style={styles.bookActionBtn}
          >
            <Text style={styles.bookActionText}>Pilih Kursi</Text>
            <ChevronRight size={14} color="#FFFFFF" style={{ marginLeft: 3 }} />
          </TouchableOpacity>
        )}
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  ticketCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.06,
        shadowRadius: 12,
      },
      android: {
        elevation: 3,
      },
      web: {
        boxShadow: "0 4px 16px rgba(15, 23, 42, 0.05)",
      } as any,
    }),
  },
  ticketCardDeparted: {
    backgroundColor: "#F9FAFB",
    borderColor: "#E5E7EB",
  },
  cardHeader: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 14,
  },
  busInfoLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  busAvatar: {
    width: 36,
    height: 36,
    borderRadius: 10,
  },
  busNameText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#111827",
  },
  busClassText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 1,
  },
  departedBadge: {
    backgroundColor: "#F3F4F6",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  departedBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    paddingHorizontal: 7,
    paddingVertical: 3,
    borderRadius: 8,
  },
  ratingBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#D97706",
  },
  timelineContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    padding: 12,
    marginBottom: 10,
  },
  timelineContainerDeparted: {
    backgroundColor: "#F1F5F9",
  },
  timeCol: {
    flex: 1,
  },
  bigTimeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
  },
  terminalNameText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  lineCol: {
    alignItems: "center",
    paddingHorizontal: 8,
  },
  durationText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 9.5,
    color: "#9CA3AF",
    marginBottom: 3,
  },
  dashedLineRow: {
    flexDirection: "row",
    alignItems: "center",
  },
  lineDot: {
    width: 4,
    height: 4,
    borderRadius: 2,
    backgroundColor: "#CBD5E1",
  },
  lineBar: {
    width: 20,
    height: 1.5,
    backgroundColor: "#E2E8F0",
  },
  transitText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9,
    color: COLORS.brandBlue,
    marginTop: 3,
  },
  stopsBox: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F1F5F9",
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    marginBottom: 12,
    marginBottom: 10,
  },
  stopsBoxText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10.5,
    color: "#475569",
    flex: 1,
  },
  nextDepartureCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F3F4F6",
    backgroundColor: "#FFFBEB",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    borderColor: "#FDE68A",
    paddingHorizontal: 10,
    paddingVertical: 7,
    borderRadius: 10,
    marginBottom: 12,
    marginBottom: 10,
  },
  nextDepartureCardText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#4B5563",
    color: "#92400E",
    flex: 1,
  },
  nextDepartureCardBold: {
    fontFamily: "PlusJakartaSans_700Bold",
    color: "#1F2937",
    color: "#78350F",
  },
  cardFooter: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    borderTopWidth: 1,
    borderTopColor: "#F1F5F9",
    paddingTop: 12,
  },
  fareLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#9CA3AF",
  },
  fareValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: COLORS.brandBlue,
  },
  tomorrowActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#475569",
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 10,
    backgroundColor: "#0F172A",
    paddingHorizontal: 14,
    paddingVertical: 9,
    borderRadius: 12,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.15,
        shadowRadius: 4,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  tomorrowActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
  bookActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 10,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.25,
        shadowRadius: 6,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  bookActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
});
