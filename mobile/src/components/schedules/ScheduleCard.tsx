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
    `Besok, ${tomorrowOption.dayNum} ${tomorrowOption.monthName} 2026 • ${depTime} WIB`;

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
            style={[styles.busAvatar, isDeparted && { opacity: 0.65 }]}
            resizeMode="cover"
          />
          <View style={styles.busMetaCol}>
            <Text
              style={[styles.busNameText, isDeparted && styles.textMutedDark]}
              numberOfLines={1}
            >
              {busName}
            </Text>
            <Text style={styles.busClassText} numberOfLines={1}>
              {busType} • {capacity} Kursi
              {availableSeats !== undefined ? ` • Sisa ${availableSeats}` : ""}
            </Text>
          </View>
        </View>

        {isDeparted ? (
          <View style={styles.departedBadge}>
            <Text style={styles.departedBadgeText}>SUDAH BERANGKAT</Text>
          </View>
        ) : (
          <View style={styles.ratingBadge}>
            <Star
              size={11}
              color="#D97706"
              fill="#D97706"
              style={{ marginRight: 3 }}
            />
            <Text style={styles.ratingBadgeText}>4.9</Text>
          </View>
        )}
      </View>

      {/* Main Departure Timeline Flow */}
      <View
        style={[
          styles.timelineContainer,
          isDeparted && styles.timelineContainerDeparted,
        ]}
      >
        {/* Left: Departure */}
        <View style={styles.timeCol}>
          <Text
            style={[styles.bigTimeText, isDeparted && styles.textMutedDark]}
          >
            {depTime}
          </Text>
          <Text style={styles.terminalNameText} numberOfLines={1}>
            {origin}
          </Text>
        </View>

        {/* Middle: Duration & Route Line (Fluid & Flexible) */}
        <View style={styles.lineCol}>
          <Text style={styles.durationText}>{duration}</Text>
          <View style={styles.dashedLineRow}>
            <View style={styles.lineDot} />
            <View style={styles.lineBar} />
            <Bus
              size={13}
              color={isDeparted ? "#94A3B8" : COLORS.brandBlue}
              style={{ marginHorizontal: 3 }}
            />
            <View style={styles.lineBar} />
            <View style={styles.lineDot} />
          </View>
          <Text
            style={[styles.transitText, isDeparted && styles.transitTextDeparted]}
            numberOfLines={1}
          >
            Via Tol Cipali
          </Text>
        </View>

        {/* Right: Arrival */}
        <View style={[styles.timeCol, styles.timeColRight]}>
          <Text
            style={[
              styles.bigTimeText,
              styles.timeRightText,
              isDeparted && styles.textMutedDark,
            ]}
          >
            {arrTime}
          </Text>
          <Text
            style={[styles.terminalNameText, styles.timeRightText]}
            numberOfLines={1}
          >
            {destination}
          </Text>
        </View>
      </View>

      {/* Intermediate Stops Strip */}
      <View style={styles.stopsBox}>
        <Navigation
          size={11}
          color="#64748B"
          style={{ marginRight: 6, flexShrink: 0 }}
        />
        <Text style={styles.stopsBoxText} numberOfLines={1}>
          Lintas: {stops.join(" • ")}
        </Text>
      </View>

      {/* Next Departure Banner if Departed Today */}
      {isDeparted && (
        <View style={styles.nextDepartureCard}>
          <Calendar
            size={13}
            color="#B45309"
            style={{ marginRight: 6, flexShrink: 0 }}
          />
          <Text style={styles.nextDepartureCardText} numberOfLines={1}>
            Trip Hari Ini Selesai •{" "}
            <Text style={styles.nextDepartureCardBold}>{nextDepartureText}</Text>
          </Text>
        </View>
      )}

      {/* Footer: Seat info & Action CTA */}
      <View style={styles.cardFooter}>
        <View style={styles.fareInfoCol}>
          <Text style={styles.fareLabel}>Harga per orang</Text>
          <Text
            style={[styles.fareValue, isDeparted && styles.fareValueDeparted]}
          >
            Rp {price}
          </Text>
        </View>

        {isDeparted ? (
          <TouchableOpacity
            activeOpacity={0.85}
            onPress={() =>
              onSelectTomorrow(
                item.next_departure_date || tomorrowOption.dateStr,
                busName,
                depTime,
              )
            }
            style={styles.tomorrowActionBtn}
          >
            <Text style={styles.tomorrowActionText}>Pesan Besok</Text>
            <ArrowRight size={13} color="#FFFFFF" style={{ marginLeft: 5 }} />
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
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
      web: {
        boxShadow: "0 2px 10px rgba(15, 23, 42, 0.04)",
      } as any,
    }),
  },
  ticketCardDeparted: {
    backgroundColor: "#FAFAFA",
    borderColor: "#E5E7EB",
  },
  cardHeader: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 12,
  },
  busInfoLeft: {
    flexDirection: "row",
    alignItems: "center",
    flex: 1,
    marginRight: 8,
  },
  busAvatar: {
    width: 38,
    height: 38,
    borderRadius: 10,
    marginRight: 10,
    backgroundColor: "#F1F5F9",
  },
  busMetaCol: {
    flex: 1,
  },
  busNameText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  busClassText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    marginTop: 1,
  },
  textMutedDark: {
    color: "#475569",
  },
  departedBadge: {
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#CBD5E1",
    paddingHorizontal: 8,
    paddingVertical: 3.5,
    borderRadius: 6,
    alignSelf: "center",
  },
  departedBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9,
    color: "#64748B",
    letterSpacing: 0.3,
  },
  ratingBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    paddingHorizontal: 8,
    paddingVertical: 3.5,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: "#FDE68A",
  },
  ratingBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10.5,
    color: "#D97706",
  },
  timelineContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F8FAFC",
    borderRadius: 14,
    paddingVertical: 12,
    paddingHorizontal: 14,
    marginBottom: 10,
  },
  timelineContainerDeparted: {
    backgroundColor: "#F1F5F9",
  },
  timeCol: {
    flex: 1.1,
  },
  timeColRight: {
    alignItems: "flex-end",
  },
  timeRightText: {
    textAlign: "right",
  },
  bigTimeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
  },
  terminalNameText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#64748B",
    marginTop: 2,
  },
  lineCol: {
    flex: 1.3,
    alignItems: "center",
    paddingHorizontal: 4,
  },
  durationText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#94A3B8",
    marginBottom: 3,
  },
  dashedLineRow: {
    flexDirection: "row",
    alignItems: "center",
    width: "100%",
    justifyContent: "center",
  },
  lineDot: {
    width: 4,
    height: 4,
    borderRadius: 2,
    backgroundColor: "#CBD5E1",
  },
  lineBar: {
    flex: 1,
    height: 1.5,
    backgroundColor: "#E2E8F0",
    maxWidth: 28,
  },
  transitText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9,
    color: COLORS.brandBlue,
    marginTop: 3,
  },
  transitTextDeparted: {
    color: "#64748B",
  },
  stopsBox: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 8,
    marginBottom: 10,
  },
  stopsBoxText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#475569",
    flex: 1,
  },
  nextDepartureCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFBEB",
    borderWidth: 1,
    borderColor: "#FDE68A",
    paddingHorizontal: 10,
    paddingVertical: 7,
    borderRadius: 8,
    marginBottom: 10,
  },
  nextDepartureCardText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#92400E",
    flex: 1,
  },
  nextDepartureCardBold: {
    fontFamily: "PlusJakartaSans_700Bold",
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
  fareInfoCol: {
    flex: 1,
    marginRight: 10,
  },
  fareLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#94A3B8",
  },
  fareValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: COLORS.brandBlue,
  },
  fareValueDeparted: {
    color: "#0F172A",
  },
  tomorrowActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#0F172A",
    paddingHorizontal: 14,
    paddingVertical: 9,
    borderRadius: 12,
    minHeight: 38,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.12,
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
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 16,
    paddingVertical: 9,
    borderRadius: 12,
    minHeight: 38,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.2,
        shadowRadius: 6,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  bookActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
});
