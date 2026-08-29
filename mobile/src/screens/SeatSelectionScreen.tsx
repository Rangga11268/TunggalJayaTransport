import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Alert,
  ActivityIndicator,
  Platform,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { useNavigation, useRoute } from "@react-navigation/native";
import { NativeStackNavigationProp } from "@react-navigation/native-stack";
import {
  ArrowLeft,
  ShieldAlert,
  Sparkles,
  ChevronRight,
  Info,
  Clock,
  Compass,
} from "lucide-react-native";
import { RootStackParamList } from "../navigation/RootNavigator";
import { COLORS } from "../theme/colors";
import apiClient from "../api/client";
import { ScreenHeader } from "../components/ScreenHeader";
import { useAuth } from "../context/AuthContext";
import { useCustomAlert } from "../context/AlertContext";

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function SeatSelectionScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<any>();
  const { scheduleId } = route.params || { scheduleId: 1 };
  const { user } = useAuth();
  const { showWarning } = useCustomAlert();

  const [loading, setLoading] = useState(true);
  const [schedule, setSchedule] = useState<any>(null);
  const [occupiedSeats, setOccupiedSeats] = useState<number[]>([
    2, 5, 8, 12, 18,
  ]);
  const [selectedSeats, setSelectedSeats] = useState<number[]>([]);
  const [isDeparted, setIsDeparted] = useState(false);

  useEffect(() => {
    fetchScheduleDetail();
  }, [scheduleId]);

  const fetchScheduleDetail = async () => {
    try {
      setLoading(true);
      const res = await apiClient.get("/schedules");
      const list = Array.isArray(res.data) ? res.data : res.data.data || [];
      const found = list.find((s: any) => s.id === scheduleId) || list[0];
      setSchedule(found);

      if (found) {
        const now = new Date();
        const schedDate = found.departure_date
          ? new Date(found.departure_date)
          : new Date();
        const [hours, minutes] = (found.departure_time || "07:00")
          .split(":")
          .map(Number);
        schedDate.setHours(hours, minutes, 0, 0);

        if (
          schedDate.getTime() < now.getTime() &&
          found.status !== "upcoming"
        ) {
          setIsDeparted(true);
        }
      }
    } catch (e) {
      console.log("Error fetching schedule details:", e);
    } finally {
      setLoading(false);
    }
  };

  const toggleSeat = (seatNumber: number) => {
    if (isDeparted) {
      Alert.alert(
        "Bus Sudah Berangkat",
        "Jadwal bus ini telah berangkat. Pemilihan kursi tidak dapat dilakukan.",
      );
      return;
    }

    if (occupiedSeats.includes(seatNumber)) {
      Alert.alert(
        "Kursi Terisi",
        `Kursi nomor ${seatNumber} sudah dipesan oleh penumpang lain.`,
      );
      return;
    }

    if (selectedSeats.includes(seatNumber)) {
      setSelectedSeats(selectedSeats.filter((s) => s !== seatNumber));
    } else {
      if (selectedSeats.length >= 4) {
        showWarning(
          "Batas Maksimal Kursi",
          "Anda dapat memilih maksimal 4 kursi penumpang per transaksi pemesanan.",
        );
        return;
      }
      setSelectedSeats([...selectedSeats, seatNumber]);
    }
  };

  const proceedToCheckout = () => {
    if (selectedSeats.length === 0) {
      showWarning(
        "Pilih Kursi Penumpang",
        "Silakan pilih minimal 1 kursi sebelum melanjutkan ke pembayaran.",
      );
      return;
    }

    navigation.navigate("Checkout", {
      scheduleId: schedule?.id || 1,
      selectedSeats,
      totalPrice: (schedule?.price || 180000) * selectedSeats.length,
    });
  };

  const totalCapacity = schedule?.bus?.capacity || 30;
  const rowsCount = Math.ceil(totalCapacity / 4);

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Pilih Kursi Penumpang"
        subtitle={`${schedule?.bus?.name || "Resi Bisma"} • ${schedule?.route?.origin || "Jakarta"} → ${schedule?.route?.destination || "Kuningan"}`}
        showBack={true}
        onBack={() => navigation.goBack()}
      />

      {/* Bus Departure Warning Notice if departed */}
      {isDeparted && (
        <View style={styles.departedBanner}>
          <ShieldAlert size={18} color="#DC2626" />
          <Text style={styles.departedText}>
            Bus ini telah diberangkatkan. Anda hanya dapat melihat ketersediaan.
          </Text>
        </View>
      )}

      {/* Seat Status Legend */}
      <View style={styles.legendContainer}>
        <View style={styles.legendItem}>
          <View style={[styles.seatLegendBox, styles.seatAvailable]} />
          <Text style={styles.legendLabel}>Tersedia</Text>
        </View>

        <View style={styles.legendItem}>
          <View style={[styles.seatLegendBox, styles.seatSelected]} />
          <Text style={styles.legendLabel}>Dipilih</Text>
        </View>

        <View style={styles.legendItem}>
          <View style={[styles.seatLegendBox, styles.seatOccupied]} />
          <Text style={styles.legendLabel}>Terisi</Text>
        </View>
      </View>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* Cabin Blueprint Container */}
        <View style={styles.cabinFrame}>
          {/* Driver Cockpit Zone */}
          <View style={styles.cockpitRow}>
            <View style={styles.doorArea}>
              <Text style={styles.cockpitText}>PINTU MASUK</Text>
            </View>
            <View style={styles.driverSeat}>
              <Compass size={18} color="#6B7280" />
              <Text style={styles.driverText}>DRIVER</Text>
            </View>
          </View>

          <View style={styles.dividerLine} />

          {/* 2-2 Seat Rows */}
          {Array.from({ length: rowsCount }).map((_, rIdx) => {
            const seat1 = rIdx * 4 + 1;
            const seat2 = rIdx * 4 + 2;
            const seat3 = rIdx * 4 + 3;
            const seat4 = rIdx * 4 + 4;

            const renderSeat = (num: number) => {
              if (num > totalCapacity) return <View style={styles.emptySlot} />;
              const isOccupied = occupiedSeats.includes(num);
              const isSelected = selectedSeats.includes(num);

              return (
                <TouchableOpacity
                  key={num}
                  activeOpacity={0.75}
                  disabled={isOccupied || isDeparted}
                  onPress={() => toggleSeat(num)}
                  style={[
                    styles.seatBox,
                    isOccupied && styles.seatBoxOccupied,
                    isSelected && styles.seatBoxSelected,
                  ]}
                >
                  <Text
                    style={[
                      styles.seatNumber,
                      isOccupied && styles.seatNumberOccupied,
                      isSelected && styles.seatNumberSelected,
                    ]}
                  >
                    {num < 10 ? `0${num}` : num}
                  </Text>
                </TouchableOpacity>
              );
            };

            return (
              <View key={rIdx} style={styles.cabinRow}>
                <View style={styles.pairLeft}>
                  {renderSeat(seat1)}
                  {renderSeat(seat2)}
                </View>

                <View style={styles.aisleGap}>
                  <Text style={styles.aisleText}>{rIdx + 1}</Text>
                </View>

                <View style={styles.pairRight}>
                  {renderSeat(seat3)}
                  {renderSeat(seat4)}
                </View>
              </View>
            );
          })}

          {/* Back Facilities (Toilet & Luggage) */}
          <View style={styles.cabinBackArea}>
            <View style={styles.toiletBox}>
              <Text style={styles.toiletText}>TOILET</Text>
            </View>
            <View style={styles.backExitBox}>
              <Text style={styles.toiletText}>PINTU DARURAT</Text>
            </View>
          </View>
        </View>

        {/* Bottom spacer */}
        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Sticky Action Bar */}
      <View style={styles.bottomBarWrapper}>
        <View style={styles.bottomBar}>
          <View style={styles.bottomLeft}>
            <Text style={styles.bottomSeatsLabel}>
              {selectedSeats.length > 0
                ? `Kursi: ${selectedSeats.join(", ")}`
                : "Pilih nomor kursi"}
            </Text>
            <Text style={styles.bottomTotalPrice}>
              Rp{" "}
              {(
                selectedSeats.length * (schedule?.price || 180000)
              ).toLocaleString("id-ID")}
            </Text>
          </View>

          <TouchableOpacity
            activeOpacity={0.85}
            disabled={isDeparted || selectedSeats.length === 0}
            onPress={proceedToCheckout}
            style={[
              styles.checkoutBtn,
              (isDeparted || selectedSeats.length === 0) &&
                styles.checkoutBtnDisabled,
            ]}
          >
            <Text style={styles.checkoutBtnText}>Lanjutkan</Text>
            <ChevronRight size={16} color="#FFFFFF" />
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  topBar: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 12,
    backgroundColor: "#FFFFFF",
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "#F4F6F9",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  topBarCenter: {
    alignItems: "center",
  },
  topBarTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 16,
    color: "#111827",
  },
  topBarSub: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 12,
    color: "#4B5563",
    marginTop: 2,
  },
  departedBanner: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    backgroundColor: "rgba(230, 0, 35, 0.08)",
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: "rgba(230, 0, 35, 0.2)",
  },
  departedText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: COLORS.brandRed,
    flex: 1,
  },
  legendContainer: {
    flexDirection: "row",
    justifyContent: "center",
    gap: 24,
    paddingVertical: 14,
    backgroundColor: "#FFFFFF",
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
  },
  legendItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 8,
  },
  seatLegendBox: {
    width: 18,
    height: 18,
    borderRadius: 5,
  },
  seatAvailable: {
    backgroundColor: "#FFFFFF",
    borderWidth: 1.5,
    borderColor: "#CBD5E1",
  },
  seatSelected: {
    backgroundColor: COLORS.brandRed,
  },
  seatOccupied: {
    backgroundColor: "#E2E8F0",
  },
  legendLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#4B5563",
  },
  scrollContent: {
    paddingHorizontal: 24,
    paddingTop: 20,
    alignItems: "center",
  },
  cabinFrame: {
    width: "100%",
    maxWidth: 340,
    backgroundColor: "#FFFFFF",
    borderRadius: 24,
    borderWidth: 1.5,
    borderColor: "#E2E8F0",
    padding: 16,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.06,
        shadowRadius: 14,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  cockpitRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 14,
  },
  doorArea: {
    paddingHorizontal: 10,
    paddingVertical: 6,
    backgroundColor: "#F1F4F8",
    borderRadius: 8,
  },
  cockpitText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#6B7280",
  },
  driverSeat: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    paddingHorizontal: 10,
    paddingVertical: 6,
    backgroundColor: "#F1F4F8",
    borderRadius: 8,
  },
  driverText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#6B7280",
  },
  dividerLine: {
    height: 1,
    backgroundColor: "#E2E8F0",
    marginBottom: 18,
  },
  cabinRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 12,
  },
  pairLeft: {
    flexDirection: "row",
    gap: 8,
  },
  pairRight: {
    flexDirection: "row",
    gap: 8,
  },
  aisleGap: {
    width: 28,
    alignItems: "center",
  },
  aisleText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#9CA3AF",
  },
  seatBox: {
    width: 44,
    height: 44,
    borderRadius: 10,
    backgroundColor: "#FFFFFF",
    borderWidth: 1.5,
    borderColor: "#CBD5E1",
    justifyContent: "center",
    alignItems: "center",
  },
  seatBoxOccupied: {
    backgroundColor: "#E2E8F0",
    borderColor: "transparent",
    opacity: 0.6,
  },
  seatBoxSelected: {
    backgroundColor: COLORS.brandRed,
    borderColor: COLORS.brandRed,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.35,
        shadowRadius: 8,
      },
      android: {
        elevation: 5,
      },
    }),
  },
  seatNumber: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#111827",
  },
  seatNumberOccupied: {
    color: "#9CA3AF",
  },
  seatNumberSelected: {
    color: "#FFFFFF",
  },
  emptySlot: {
    width: 44,
    height: 44,
  },
  cabinBackArea: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginTop: 14,
    paddingTop: 14,
    borderTopWidth: 1,
    borderTopColor: "#E2E8F0",
  },
  toiletBox: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    backgroundColor: "#F1F4F8",
    borderRadius: 8,
  },
  backExitBox: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    backgroundColor: "#F1F4F8",
    borderRadius: 8,
  },
  toiletText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#6B7280",
  },
  bottomBarWrapper: {
    position: "absolute",
    bottom: Platform.OS === "ios" ? 28 : 20,
    left: 20,
    right: 20,
  },
  bottomBar: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingVertical: 12,
    paddingHorizontal: 18,
    borderRadius: 36,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.12,
        shadowRadius: 18,
      },
      android: {
        elevation: 8,
      },
    }),
  },
  bottomLeft: {},
  bottomSeatsLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#4B5563",
  },
  bottomTotalPrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#111827",
  },
  checkoutBtn: {
    flexDirection: "row",
    alignItems: "center",
    gap: 6,
    backgroundColor: COLORS.brandRed,
    paddingVertical: 12,
    paddingHorizontal: 22,
    borderRadius: 24,
  },
  checkoutBtnDisabled: {
    backgroundColor: "#CBD5E1",
  },
  checkoutBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
});
