import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Share,
  Platform,
  ActivityIndicator,
  Linking,
} from "react-native";
import { COLORS } from "../theme/colors";
import api from "../api/client";
import { formatIndonesianDate, formatIndonesianTime } from "../utils/format";
import { ScreenHeader } from "../components/ScreenHeader";
import {
  Share2,
  QrCode,
  Bus,
  CheckCircle2,
  Calendar,
  Clock,
  User,
  Phone,
  Armchair,
  CreditCard,
  ShieldCheck,
  ArrowRight,
  Download,
  MessageCircle,
} from "lucide-react-native";

export default function TicketDetailScreen({ navigation, route }: any) {
  const {
    booking: initialBooking,
    schedule: initialSchedule,
    selectedSeats: initialSeats,
    bookingId,
  } = route.params || {};

  const [booking, setBooking] = useState<any>(initialBooking || null);
  const [schedule, setSchedule] = useState<any>(
    initialSchedule || initialBooking?.schedule || null,
  );
  const [selectedSeats, setSelectedSeats] = useState<string[]>(
    initialSeats || [],
  );
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (!booking && bookingId) {
      fetchBookingDetail(bookingId);
    } else if (booking && !schedule && booking.schedule) {
      setSchedule(booking.schedule);
    }
  }, [bookingId]);

  const fetchBookingDetail = async (id: number) => {
    try {
      setLoading(true);
      const res = await api.get(`/bookings/${id}`);
      if (res.data?.data) {
        const b = res.data.data;
        setBooking(b);
        setSchedule(b.schedule);
        if (b.seat_numbers) {
          if (Array.isArray(b.seat_numbers)) {
            setSelectedSeats(b.seat_numbers);
          } else if (typeof b.seat_numbers === "string") {
            setSelectedSeats(
              b.seat_numbers.split(",").map((s: string) => s.trim()),
            );
          }
        }
      }
    } catch (e) {
      console.error("Error loading booking detail:", e);
    } finally {
      setLoading(false);
    }
  };

  const bookingCode = booking?.booking_code || `TJ-BK${booking?.id || 101}`;
  const origin = schedule?.route?.origin || "Kuningan";
  const destination = schedule?.route?.destination || "Jakarta (Pulogebang)";
  const busName = schedule?.bus?.name || "Resi Bisma";
  const busType = schedule?.bus?.bus_type || "Executive";
  const plateNumber = schedule?.bus?.plate_number || "E 7777 TJ";
  const depTime = formatIndonesianTime(schedule?.departure_time, "07:00");
  const arrTime = formatIndonesianTime(schedule?.arrival_time, "11:00");
  const passengerName = booking?.passenger_name || "Rangga Putra";
  const passengerPhone = booking?.passenger_phone || "081234567890";
  const date = formatIndonesianDate(
    booking?.booking_date || booking?.created_at || "2026-08-30",
    false,
  );
  const dateShort = formatIndonesianDate(
    booking?.booking_date || booking?.created_at || "2026-08-30",
    true,
  );
  const totalPrice = Number(
    booking?.total_price || booking?.total_amount || 260000,
  ).toLocaleString("id-ID");
  const seatsText =
    selectedSeats.length > 0
      ? selectedSeats.join(", ")
      : booking?.seat_numbers || "1";

  const onShare = async () => {
    try {
      await Share.share({
        message: `E-Tiket PO Tunggal Jaya Transport\nKode Booking: ${bookingCode}\nRute: ${origin} → ${destination}\nArmada: ${busName} (${plateNumber})\nTanggal: ${date} • ${depTime} WIB\nKursi: ${seatsText}\nStatus: LUNAS & TERKONFIRMASI`,
      });
    } catch {}
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={COLORS.brandBlue} />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="E-Tiket Boarding Pass"
        subtitle="PO Tunggal Jaya Transport"
        rightElement={
          <TouchableOpacity
            style={styles.headerIconBtn}
            onPress={onShare}
            activeOpacity={0.7}
          >
            <Share2 size={18} color="#111827" />
          </TouchableOpacity>
        }
      />

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Luxury Perforated Boarding Pass Card (Web Matching Layout) */}
        <View style={styles.boardingPass}>
          {/* Top Brand Header Strip */}
          <View style={styles.passHeader}>
            <View style={styles.passBrand}>
              <View style={styles.webStubBadge}>
                <Text style={styles.webStubBadgeText}>E - TICKET</Text>
              </View>
              <View style={{ flexShrink: 1 }}>
                <Text style={styles.passBrandTitle} numberOfLines={1}>
                  Tunggal Jaya Transport
                </Text>
                <Text style={styles.passBrandSub} numberOfLines={1}>
                  Official Boarding Pass
                </Text>
              </View>
            </View>
            <View style={styles.confirmedBadge}>
              <CheckCircle2
                size={12}
                color="#059669"
                style={{ marginRight: 4 }}
              />
              <Text style={styles.confirmedText}>PAID / LUNAS</Text>
            </View>
          </View>

          {/* Route Highlight Banner */}
          <View style={styles.routeHighlightBox}>
            <View style={styles.routeCityBox}>
              <Text style={styles.routeCityLabel}>DARI</Text>
              <Text style={styles.routeCityName} numberOfLines={1}>
                {origin}
              </Text>
              <Text style={styles.routeTime}>{depTime} WIB</Text>
            </View>

            <View style={styles.routeMiddle}>
              <View style={styles.busIconCircle}>
                <Bus size={18} color={COLORS.brandBlue} />
              </View>
              <View
                style={{ flexDirection: "row", alignItems: "center", gap: 3 }}
              >
                <View
                  style={{ width: 14, height: 1.5, backgroundColor: "#CBD5E1" }}
                />
                <ArrowRight size={13} color="#94A3B8" />
              </View>
            </View>

            <View style={[styles.routeCityBox, { alignItems: "flex-end" }]}>
              <Text style={styles.routeCityLabel}>TUJUAN</Text>
              <Text
                style={[styles.routeCityName, { textAlign: "right" }]}
                numberOfLines={1}
              >
                {destination}
              </Text>
              <Text style={styles.routeTime}>{arrTime} WIB</Text>
            </View>
          </View>

          {/* Tear Line / Notches */}
          <View style={styles.tearLineContainer}>
            <View style={styles.leftNotch} />
            <View style={styles.dashedLine} />
            <View style={styles.rightNotch} />
          </View>

          {/* Web-matching Passenger & Fleet Data Grid */}
          <View style={styles.detailsGrid}>
            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>PASSENGER</Text>
              <View style={styles.detailBox}>
                <Text style={styles.detailValue} numberOfLines={1}>
                  {passengerName.toUpperCase()}
                </Text>
              </View>
            </View>

            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>BUS NAME</Text>
              <View style={styles.detailBox}>
                <Text style={styles.detailValue} numberOfLines={1}>
                  {busName.toUpperCase()}
                </Text>
              </View>
            </View>

            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>DATE • TIME</Text>
              <View style={styles.detailBox}>
                <Text style={styles.detailValue} numberOfLines={1}>
                  {dateShort} • {depTime}
                </Text>
              </View>
            </View>

            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>SEAT</Text>
              <View style={styles.detailBox}>
                <Text style={[styles.detailValue, { color: COLORS.brandBlue }]}>
                  {seatsText}
                </Text>
              </View>
            </View>

            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>CLASS</Text>
              <View style={styles.detailBox}>
                <Text style={styles.detailValue} numberOfLines={1}>
                  {busType.toUpperCase()}
                </Text>
              </View>
            </View>

            <View style={styles.detailItem}>
              <Text style={styles.detailLabel}>STATUS</Text>
              <View style={[styles.detailBox, { backgroundColor: "#ECFDF5" }]}>
                <Text style={[styles.detailValue, { color: "#059669" }]}>
                  LUNAS & TERVERIFIKASI
                </Text>
              </View>
            </View>
          </View>

          {/* Route Path Description Bar */}
          <View style={styles.routeDescBar}>
            <Text style={styles.routeDescLabel}>ROUTE</Text>
            <View style={styles.routeDescBox}>
              <Text style={styles.routeDescText}>
                {origin.toUpperCase()} &gt;&gt; {destination.toUpperCase()} (VIA
                TOL CIPALI)
              </Text>
            </View>
          </View>

          {/* Total Price Bar */}
          <View style={styles.totalPriceBar}>
            <Text style={styles.totalPriceLabel}>TOTAL PAYMENT</Text>
            <Text style={styles.totalPriceAmount}>Rp {totalPrice}</Text>
          </View>

          {/* QR Code & Barcode Boarding Pass Section */}
          <View style={styles.qrSection}>
            <Text style={styles.scanCheckInLabel}>Scan to check in</Text>
            <View style={styles.qrPlaceholder}>
              <QrCode size={130} color="#111827" />
            </View>
            <Text style={styles.qrCodeText}>ID {bookingCode}</Text>
            <Text style={styles.qrInstruction}>
              Tunjukkan QR Code ini kepada kondektur atau kru agen PO Tunggal
              Jaya saat proses boarding keberangkatan.
            </Text>
          </View>
        </View>

        {/* Security & Official Warranty Guarantee */}
        <View style={styles.guaranteeBox}>
          <ShieldCheck size={18} color="#059669" style={{ marginRight: 8 }} />
          <Text style={styles.guaranteeText}>
            E-Tiket ini merupakan bukti tiket sah terdaftar resmi di manifes
            keberangkatan PO Tunggal Jaya Transport.
          </Text>
        </View>

        {/* Action Buttons Row */}
        <View style={styles.actionsBtnRow}>
          <TouchableOpacity
            style={styles.shareBtn}
            activeOpacity={0.85}
            onPress={onShare}
          >
            <Share2 size={16} color="#FFFFFF" style={{ marginRight: 6 }} />
            <Text style={styles.shareBtnText}>Bagikan Tiket</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.homeBtn}
            activeOpacity={0.85}
            onPress={() => navigation.navigate("MainTabs", { screen: "Home" })}
          >
            <Text style={styles.homeBtnText}>Kembali ke Beranda</Text>
          </TouchableOpacity>
        </View>

        <View style={{ height: 40 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  loadingContainer: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
    justifyContent: "center",
    alignItems: "center",
  },
  headerIconBtn: {
    width: 38,
    height: 38,
    borderRadius: 19,
    backgroundColor: "#F8FAFC",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    justifyContent: "center",
    alignItems: "center",
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
    width: "100%",
    maxWidth: 560,
    alignSelf: "center",
  },
  boardingPass: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    overflow: "hidden",
    marginBottom: 16,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.08,
        shadowRadius: 14,
      },
      android: {
        elevation: 4,
      },
      web: {
        boxShadow: "0 4px 16px rgba(15, 23, 42, 0.06)",
      } as any,
    }),
  },
  passHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 14,
    paddingVertical: 12,
    backgroundColor: "#F8FAFC",
    borderBottomWidth: 1,
    borderBottomColor: "#E2E8F0",
    gap: 8,
  },
  passBrand: {
    flexDirection: "row",
    alignItems: "center",
    gap: 8,
    flex: 1,
  },
  webStubBadge: {
    backgroundColor: "#10207A",
    paddingHorizontal: 6,
    paddingVertical: 3,
    borderRadius: 5,
  },
  webStubBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9,
    color: "#FFFFFF",
    letterSpacing: 0.5,
  },
  passBrandTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 12.5,
    color: "#111827",
  },
  passBrandSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 9.5,
    color: "#6B7280",
  },
  confirmedBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#ECFDF5",
    borderWidth: 1,
    borderColor: "#A7F3D0",
    paddingHorizontal: 7,
    paddingVertical: 3,
    borderRadius: 6,
    flexShrink: 0,
  },
  confirmedText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#059669",
  },
  routeHighlightBox: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 18,
    paddingVertical: 16,
  },
  routeCityBox: {
    flex: 1,
  },
  routeCityLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#9CA3AF",
    marginBottom: 2,
    letterSpacing: 0.5,
  },
  routeCityName: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#111827",
    marginBottom: 2,
  },
  routeTime: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  routeMiddle: {
    alignItems: "center",
    paddingHorizontal: 8,
  },
  busIconCircle: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "rgba(37, 99, 235, 0.08)",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 4,
  },
  tearLineContainer: {
    flexDirection: "row",
    alignItems: "center",
    height: 20,
    overflow: "hidden",
    position: "relative",
  },
  leftNotch: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: COLORS.bgDark,
    marginLeft: -10,
  },
  dashedLine: {
    flex: 1,
    height: 1,
    borderWidth: 1,
    borderColor: "#CBD5E1",
    borderStyle: "dashed",
  },
  rightNotch: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: COLORS.bgDark,
    marginRight: -10,
  },
  detailsGrid: {
    flexDirection: "row",
    flexWrap: "wrap",
    paddingHorizontal: 16,
    paddingVertical: 12,
    gap: 8,
  },
  detailItem: {
    width: "48%",
    marginBottom: 4,
  },
  detailLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 8.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
    marginBottom: 3,
  },
  detailBox: {
    backgroundColor: "#F1F5F9",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 6,
  },
  detailValue: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#1E293B",
  },
  routeDescBar: {
    paddingHorizontal: 16,
    paddingVertical: 8,
  },
  routeDescLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 8.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
    marginBottom: 3,
  },
  routeDescBox: {
    backgroundColor: "#F1F5F9",
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 6,
    borderLeftWidth: 4,
    borderLeftColor: COLORS.brandBlue,
  },
  routeDescText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#1E293B",
  },
  totalPriceBar: {
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderTopWidth: 1,
    borderTopColor: "#F1F5F9",
  },
  totalPriceLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 8.5,
    color: "#9CA3AF",
    letterSpacing: 0.5,
  },
  totalPriceAmount: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: COLORS.brandBlue,
    marginTop: 2,
  },
  qrSection: {
    alignItems: "center",
    padding: 20,
    backgroundColor: "#F8FAFC",
    borderTopWidth: 1,
    borderTopColor: "#E2E8F0",
  },
  scanCheckInLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: "#10207A",
    letterSpacing: 0.5,
    marginBottom: 10,
  },
  qrPlaceholder: {
    padding: 14,
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 10,
  },
  qrCodeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#10207A",
    letterSpacing: 1,
    marginBottom: 6,
  },
  qrInstruction: {
    fontFamily: "PlusJakartaSans_400Regular",
    fontSize: 11,
    color: "#6B7280",
    textAlign: "center",
    lineHeight: 16,
    maxWidth: 280,
  },
  guaranteeBox: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(5, 150, 105, 0.08)",
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: "rgba(5, 150, 105, 0.2)",
    marginBottom: 16,
  },
  guaranteeText: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#059669",
    lineHeight: 16,
  },
  actionsBtnRow: {
    flexDirection: "row",
    gap: 10,
  },
  shareBtn: {
    flex: 1,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    height: 48,
    borderRadius: 14,
    backgroundColor: "#10207A",
  },
  shareBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  homeBtn: {
    flex: 1,
    height: 48,
    borderRadius: 14,
    backgroundColor: "#F1F5F9",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  homeBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#1E293B",
  },
});
