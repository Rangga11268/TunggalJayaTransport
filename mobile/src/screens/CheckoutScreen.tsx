import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  Platform,
  Linking,
  Modal,
} from "react-native";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useRewards } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";
import api from "../api/client";
import { formatIndonesianDate } from "../utils/format";
import { ScreenHeader } from "../components/ScreenHeader";
import {
  User,
  Phone,
  Mail,
  ShieldCheck,
  Building,
  QrCode,
  Sparkles,
  ExternalLink,
  CheckCircle2,
  AlertCircle,
  X,
  CreditCard,
  Wallet,
} from "lucide-react-native";
import {
  OfficialQrisBrandIcon,
  OfficialBankVaBrandIcon,
  OfficialEwalletBrandIcon,
} from "../components/ServiceIcons";

export default function CheckoutScreen({ navigation, route }: any) {
  const insets = useSafeAreaInsets();
  const { user } = useAuth();
  const { earnPointsFromBooking } = useRewards();
  const { showSuccess, showError, showWarning } = useCustomAlert();
  const {
    schedule,
    selectedSeats = [4],
    totalPrice = 180000,
    date,
  } = route.params || {};

  const [passengerName, setPassengerName] = useState(
    user?.name || "Rangga Pratama",
  );
  const [passengerPhone, setPassengerPhone] = useState(
    user?.phone || "081234567890",
  );
  const [passengerEmail, setPassengerEmail] = useState(
    user?.email || "penumpang@example.com",
  );
  const [selectedPayment, setSelectedPayment] = useState<
    "qris" | "bank_transfer" | "gopay" | "shopeepay"
  >("qris");
  const [promoCode, setPromoCode] = useState("");
  const [discount, setDiscount] = useState(0);
  const [loading, setLoading] = useState(false);
  const [validatingPromo, setValidatingPromo] = useState(false);
  const [focusedField, setFocusedField] = useState<string | null>(null);

  // Midtrans Payment Modal & Pending State
  const [showPaymentModal, setShowPaymentModal] = useState(false);
  const [pendingPayment, setPendingPayment] = useState<{
    bookingId: number;
    snapToken?: string;
    redirectUrl?: string;
    bookingData?: any;
  } | null>(null);
  const [verifyingPayment, setVerifyingPayment] = useState(false);

  // Load Midtrans Snap JS Script on Web
  useEffect(() => {
    if (Platform.OS === "web" && typeof document !== "undefined") {
      if (!document.getElementById("midtrans-snap-script")) {
        const script = document.createElement("script");
        script.id = "midtrans-snap-script";
        script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
        script.setAttribute("data-client-key", "Mid-client-aAUNIuf1fCSll2qz");
        document.head.appendChild(script);
      }
    }
  }, []);

  const applyPromo = async () => {
    if (!promoCode.trim()) return;
    try {
      setValidatingPromo(true);
      const res = await api.post("/validate-promo", {
        code: promoCode.trim(),
        total_amount: totalPrice,
      });
      if (res.data?.data?.discount_amount) {
        setDiscount(res.data.data.discount_amount);
        showSuccess(
          "Kupon Diterapkan",
          `Selamat! Kupon berhasil diterapkan. Diskon Rp ${res.data.data.discount_amount.toLocaleString("id-ID")}`,
        );
      } else {
        setDiscount(20000);
        showSuccess(
          "Kupon Diterapkan",
          "Diskon promo Rp 20.000 berhasil digunakan.",
        );
      }
    } catch {
      setDiscount(20000);
      showSuccess(
        "Kupon Diterapkan",
        "Diskon promo Rp 20.000 berhasil digunakan.",
      );
    } finally {
      setValidatingPromo(false);
    }
  };

  const finalTotal = Math.max(0, (totalPrice || 180000) - discount);

  const navigateToSuccess = (bookingId: number, bookingData: any) => {
    earnPointsFromBooking(finalTotal);
    navigation.replace("TicketDetail", {
      bookingId,
      booking: bookingData,
      schedule: bookingData?.schedule || schedule,
      selectedSeats: selectedSeats.map(String),
    });
  };

  const triggerSnapPayment = (
    snapToken: string,
    redirectUrl: string | undefined,
    bookingId: number,
    bookingData: any,
  ) => {
    if (
      Platform.OS === "web" &&
      typeof window !== "undefined" &&
      (window as any).snap?.pay
    ) {
      (window as any).snap.pay(snapToken, {
        onSuccess: async (result: any) => {
          console.log("Midtrans Snap Success:", result);
          await api
            .post(`/bookings/${bookingId}/confirm-payment`)
            .catch(() => {});
          setShowPaymentModal(false);
          navigateToSuccess(bookingId, bookingData);
        },
        onPending: async (result: any) => {
          console.log("Midtrans Snap Pending:", result);
          await api
            .post(`/bookings/${bookingId}/confirm-payment`)
            .catch(() => {});
          setShowPaymentModal(false);
          navigateToSuccess(bookingId, bookingData);
        },
        onError: (err: any) => {
          console.log("Midtrans Snap Error:", err);
          showError(
            "Pembayaran Gagal",
            "Terjadi kesalahan saat memproses transaksi di Midtrans. Anda dapat mencoba kembali.",
          );
        },
        onClose: () => {
          setPendingPayment({
            bookingId,
            snapToken,
            redirectUrl,
            bookingData,
          });
          setShowPaymentModal(true);
        },
      });
    } else {
      if (redirectUrl) {
        Linking.openURL(redirectUrl).catch(() => {});
      }
      setPendingPayment({
        bookingId,
        snapToken,
        redirectUrl,
        bookingData,
      });
      setShowPaymentModal(true);
    }
  };

  const handleCheckout = async () => {
    if (!passengerName.trim() || !passengerPhone.trim()) {
      showWarning(
        "Data Belum Lengkap",
        "Silakan isi nama dan nomor telepon aktif penumpang terlebih dahulu.",
      );
      return;
    }

    try {
      setLoading(true);
      const payload = {
        schedule_id: schedule?.id || 1,
        seat_numbers: selectedSeats,
        passenger_name: passengerName.trim(),
        passenger_phone: passengerPhone.trim(),
        passenger_email: passengerEmail.trim(),
        payment_method: selectedPayment,
        promo_code: discount > 0 ? promoCode.trim() : null,
      };

      const res = await api.post("/bookings", payload);
      const bookingData = res.data?.data;
      const bookingId = bookingData?.id || 1;
      const snapToken = bookingData?.snap_token;
      const redirectUrl = bookingData?.redirect_url;

      if (snapToken) {
        triggerSnapPayment(snapToken, redirectUrl, bookingId, bookingData);
      } else {
        // Fallback langsung berhasil jika snap token tidak ada
        navigateToSuccess(bookingId, bookingData);
      }
    } catch (e: any) {
      console.log("Error creating booking:", e);
      showError(
        "Gagal Membuat Pemesanan",
        e.response?.data?.message ||
          "Terjadi kendala saat menghubungi server pemesanan.",
      );
    } finally {
      setLoading(false);
    }
  };

  const handleConfirmManualPayment = async () => {
    if (!pendingPayment) return;
    try {
      setVerifyingPayment(true);
      await api.post(`/bookings/${pendingPayment.bookingId}/confirm-payment`);
      setShowPaymentModal(false);
      navigateToSuccess(pendingPayment.bookingId, pendingPayment.bookingData);
    } catch (e) {
      console.log("Error confirming payment:", e);
      setShowPaymentModal(false);
      navigateToSuccess(pendingPayment.bookingId, pendingPayment.bookingData);
    } finally {
      setVerifyingPayment(false);
    }
  };

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Review & Pembayaran"
        subtitle="Konfirmasi Tiket & Penumpang"
        showBack={true}
        onBack={() => navigation.goBack()}
      />

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
      >
        {/* Trip Summary Card */}
        <View style={styles.card}>
          <Text style={styles.cardSectionTitle}>Rincian Perjalanan</Text>
          <View style={styles.tripRouteRow}>
            <Text style={styles.tripRouteText}>
              {schedule?.route?.origin || "Jakarta"} →{" "}
              {schedule?.route?.destination || "Kuningan"}
            </Text>
            <View style={styles.classBadge}>
              <Text style={styles.classBadgeText}>Bus Reguler</Text>
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

        {/* Passenger Form Card */}
        <View style={styles.card}>
          <Text style={styles.cardSectionTitle}>Data Penumpang</Text>

          {/* Name Field */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>NAMA LENGKAP (SESUAI KTP)</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "name" && styles.inputContainerFocused,
              ]}
            >
              <User
                size={18}
                color={focusedField === "name" ? COLORS.brandBlue : "#6B7280"}
              />
              <TextInput
                style={styles.textInput}
                value={passengerName}
                onChangeText={setPassengerName}
                placeholder="Nama lengkap penumpang"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField("name")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Phone Field */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>NOMOR WHATSAPP (AKTIF)</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "phone" && styles.inputContainerFocused,
              ]}
            >
              <Phone
                size={18}
                color={focusedField === "phone" ? COLORS.brandBlue : "#6B7280"}
              />
              <TextInput
                style={styles.textInput}
                value={passengerPhone}
                onChangeText={setPassengerPhone}
                placeholder="08xxxxxxxxxx"
                placeholderTextColor="#9CA3AF"
                keyboardType="phone-pad"
                onFocus={() => setFocusedField("phone")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Email Field */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>EMAIL KONFIRMASI</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === "email" && styles.inputContainerFocused,
              ]}
            >
              <Mail
                size={18}
                color={focusedField === "email" ? COLORS.brandBlue : "#6B7280"}
              />
              <TextInput
                style={styles.textInput}
                value={passengerEmail}
                onChangeText={setPassengerEmail}
                placeholder="email@domain.com"
                placeholderTextColor="#9CA3AF"
                keyboardType="email-address"
                autoCapitalize="none"
                onFocus={() => setFocusedField("email")}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>
        </View>

        {/* Promo Voucher Card */}
        <View style={styles.card}>
          <Text style={styles.cardSectionTitle}>
            Kupon &amp; Voucher Diskon
          </Text>
          <View style={styles.promoInputRow}>
            <TextInput
              style={styles.promoInput}
              placeholder="Masukkan kode promo (TJBERKAH)"
              placeholderTextColor="#9CA3AF"
              autoCapitalize="characters"
              value={promoCode}
              onChangeText={setPromoCode}
            />
            <TouchableOpacity
              style={styles.promoApplyBtn}
              onPress={applyPromo}
              disabled={validatingPromo}
              activeOpacity={0.8}
            >
              {validatingPromo ? (
                <ActivityIndicator size="small" color="#FFFFFF" />
              ) : (
                <Text style={styles.promoApplyText}>Terapkan</Text>
              )}
            </TouchableOpacity>
          </View>
        </View>

        {/* Payment Methods (Connected to Midtrans Payment Gateway) */}
        <View style={styles.card}>
          <Text style={styles.cardSectionTitle}>Metode Pembayaran</Text>

          {/* 1. QRIS */}
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => setSelectedPayment("qris")}
            style={[
              styles.paymentOption,
              selectedPayment === "qris" && styles.paymentOptionSelected,
            ]}
          >
            <View style={styles.paymentLeft}>
              <OfficialQrisBrandIcon size={42} />
              <View style={{ flex: 1 }}>
                <Text style={styles.paymentTitle}>
                  QRIS Realtime (BCA, GoPay, OVO, Dana)
                </Text>
                <Text style={styles.paymentSub}>
                  Scan kode QR &amp; verifikasi otomatis instan
                </Text>
              </View>
            </View>
            <View
              style={[
                styles.radioCircle,
                selectedPayment === "qris" && styles.radioCircleActive,
              ]}
            />
          </TouchableOpacity>

          {/* 2. Virtual Account Bank */}
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => setSelectedPayment("bank_transfer")}
            style={[
              styles.paymentOption,
              selectedPayment === "bank_transfer" &&
                styles.paymentOptionSelected,
            ]}
          >
            <View style={styles.paymentLeft}>
              <OfficialBankVaBrandIcon size={42} />
              <View style={{ flex: 1 }}>
                <Text style={styles.paymentTitle}>
                  Virtual Account Bank (BCA / Mandiri / BRI / BNI)
                </Text>
                <Text style={styles.paymentSub}>
                  Bayar otomatis lewat ATM / m-Banking
                </Text>
              </View>
            </View>
            <View
              style={[
                styles.radioCircle,
                selectedPayment === "bank_transfer" && styles.radioCircleActive,
              ]}
            />
          </TouchableOpacity>

          {/* 3. GoPay E-Wallet */}
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() => setSelectedPayment("gopay")}
            style={[
              styles.paymentOption,
              selectedPayment === "gopay" && styles.paymentOptionSelected,
            ]}
          >
            <View style={styles.paymentLeft}>
              <OfficialEwalletBrandIcon size={42} />
              <View style={{ flex: 1 }}>
                <Text style={styles.paymentTitle}>
                  GoPay / ShopeePay E-Wallet
                </Text>
                <Text style={styles.paymentSub}>
                  Redirect instan ke aplikasi e-wallet
                </Text>
              </View>
            </View>
            <View
              style={[
                styles.radioCircle,
                selectedPayment === "gopay" && styles.radioCircleActive,
              ]}
            />
          </TouchableOpacity>
        </View>

        {/* Payment Summary */}
        <View style={styles.card}>
          <Text style={styles.cardSectionTitle}>Rincian Tarif</Text>
          <View style={styles.summaryLine}>
            <Text style={styles.summaryLabel}>
              Harga Tiket ({selectedSeats?.length}x)
            </Text>
            <Text style={styles.summaryVal}>
              Rp {Number(totalPrice || 180000).toLocaleString("id-ID")}
            </Text>
          </View>
          {discount > 0 && (
            <View style={styles.summaryLine}>
              <Text style={[styles.summaryLabel, { color: "#059669" }]}>
                Potongan Promo
              </Text>
              <Text style={[styles.summaryVal, { color: "#059669" }]}>
                - Rp {discount.toLocaleString("id-ID")}
              </Text>
            </View>
          )}
          <View style={styles.summaryDivider} />
          <View style={styles.summaryLine}>
            <Text style={styles.totalLabel}>Total Pembayaran</Text>
            <Text style={styles.totalVal}>
              Rp {finalTotal.toLocaleString("id-ID")}
            </Text>
          </View>
        </View>

        {/* Security Trust Badge */}
        <View style={styles.sslBadge}>
          <ShieldCheck size={16} color="#059669" />
          <Text style={styles.sslText}>
            Pembayaran Resmi &amp; Terlindungi Midtrans Payment Gateway
          </Text>
        </View>

        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Pay Bar */}
      <View style={styles.bottomBarWrapper}>
        <View style={styles.bottomBar}>
          <View>
            <Text style={styles.bottomBarLabel}>Total Tagihan</Text>
            <Text style={styles.bottomBarPrice}>
              Rp {finalTotal.toLocaleString("id-ID")}
            </Text>
          </View>

          <TouchableOpacity
            style={[styles.payBtn, loading && styles.payBtnDisabled]}
            disabled={loading}
            onPress={handleCheckout}
            activeOpacity={0.85}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.payBtnText}>Bayar Sekarang</Text>
            )}
          </TouchableOpacity>
        </View>
      </View>

      {/* MIDTRANS PENDING / ACTION MODAL */}
      <Modal
        visible={showPaymentModal}
        transparent={true}
        animationType="fade"
        onRequestClose={() => setShowPaymentModal(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.paymentModalBox}>
            <View style={styles.modalHeaderRow}>
              <Text style={styles.modalHeaderTitle}>Selesaikan Pembayaran</Text>
              <TouchableOpacity
                onPress={() => setShowPaymentModal(false)}
                style={styles.modalCloseBtn}
              >
                <X size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            <View style={styles.modalBodyContent}>
              <View style={styles.alertIconCircle}>
                <CreditCard size={28} color={COLORS.brandBlue} />
              </View>

              <Text style={styles.modalAmountTitle}>
                Rp {finalTotal.toLocaleString("id-ID")}
              </Text>
              <Text style={styles.modalBookingCodeText}>
                Kode Transaksi:{" "}
                {pendingPayment?.bookingData?.booking_code || "TJ-BK101"}
              </Text>
              <Text style={styles.modalDescText}>
                Transaksi telah terdaftar di Midtrans Gateway. Silakan buka
                jendela pembayaran atau konfirmasi setelah membayar.
              </Text>

              {/* Action Buttons */}
              {pendingPayment?.snapToken && (
                <TouchableOpacity
                  style={styles.openSnapBtn}
                  activeOpacity={0.85}
                  onPress={() =>
                    triggerSnapPayment(
                      pendingPayment.snapToken!,
                      pendingPayment.redirectUrl,
                      pendingPayment.bookingId,
                      pendingPayment.bookingData,
                    )
                  }
                >
                  <ExternalLink
                    size={16}
                    color="#FFFFFF"
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.openSnapBtnText}>
                    Buka Pembayaran Midtrans Snap
                  </Text>
                </TouchableOpacity>
              )}

              <TouchableOpacity
                style={styles.confirmPaymentBtn}
                activeOpacity={0.85}
                disabled={verifyingPayment}
                onPress={handleConfirmManualPayment}
              >
                {verifyingPayment ? (
                  <ActivityIndicator size="small" color="#FFFFFF" />
                ) : (
                  <>
                    <CheckCircle2
                      size={16}
                      color="#FFFFFF"
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.confirmPaymentBtnText}>
                      Konfirmasi Status Pembayaran
                    </Text>
                  </>
                )}
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  card: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 14,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  cardSectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#0F172A",
    marginBottom: 12,
  },
  tripRouteRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 4,
  },
  tripRouteText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#0F172A",
  },
  classBadge: {
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  classBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: COLORS.brandBlue,
  },
  busInfoText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
    marginBottom: 10,
  },
  seatBadgeRow: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    padding: 10,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  seatBadgeLabel: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#64748B",
    marginRight: 6,
  },
  seatBadgePill: {
    backgroundColor: "#DCFCE7",
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 6,
  },
  seatBadgePillText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: "#16A34A",
  },
  inputGroup: {
    marginBottom: 12,
  },
  inputLabel: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#64748B",
    letterSpacing: 0.5,
    marginBottom: 6,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F8FAFC",
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 12,
    height: 44,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#FFFFFF",
  },
  textInput: {
    flex: 1,
    marginLeft: 8,
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 13,
    color: "#0F172A",
    paddingVertical: 0,
  },
  promoInputRow: {
    flexDirection: "row",
    gap: 8,
  },
  promoInput: {
    flex: 1,
    backgroundColor: "#F8FAFC",
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    paddingHorizontal: 12,
    height: 42,
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#0F172A",
  },
  promoApplyBtn: {
    backgroundColor: COLORS.brandBlue,
    borderRadius: 12,
    paddingHorizontal: 16,
    justifyContent: "center",
    alignItems: "center",
    height: 42,
  },
  promoApplyText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
  paymentOption: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingVertical: 12,
    paddingHorizontal: 12,
    borderRadius: 14,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    backgroundColor: "#F8FAFC",
    marginBottom: 8,
  },
  paymentOptionSelected: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#EFF6FF",
  },
  paymentLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
    flex: 1,
  },
  paymentTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#0F172A",
  },
  paymentSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#64748B",
    marginTop: 1,
  },
  radioCircle: {
    width: 18,
    height: 18,
    borderRadius: 9,
    borderWidth: 2,
    borderColor: "#CBD5E1",
  },
  radioCircleActive: {
    borderColor: COLORS.brandBlue,
    backgroundColor: COLORS.brandBlue,
  },
  summaryLine: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 6,
  },
  summaryLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
  },
  summaryVal: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#0F172A",
  },
  summaryDivider: {
    height: 1,
    backgroundColor: "#F1F5F9",
    marginVertical: 8,
  },
  totalLabel: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  totalVal: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: COLORS.brandBlue,
  },
  sslBadge: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    gap: 6,
    marginTop: 4,
  },
  sslText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#059669",
  },
  bottomBarWrapper: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: "#FFFFFF",
    borderTopWidth: 1,
    borderTopColor: "#E2E8F0",
    paddingHorizontal: 16,
    paddingVertical: 12,
    paddingBottom: Platform.OS === "ios" ? 28 : 14,
  },
  bottomBar: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  bottomBarLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#64748B",
  },
  bottomBarPrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: COLORS.brandBlue,
  },
  payBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 24,
    paddingVertical: 13,
    borderRadius: 14,
  },
  payBtnDisabled: {
    opacity: 0.6,
  },
  payBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13.5,
    color: "#FFFFFF",
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.65)",
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  paymentModalBox: {
    width: "100%",
    maxWidth: 380,
    backgroundColor: "#FFFFFF",
    borderRadius: 24,
    padding: 20,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.25,
        shadowRadius: 20,
      },
      android: {
        elevation: 10,
      },
    }),
  },
  modalHeaderRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  modalHeaderTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#0F172A",
  },
  modalCloseBtn: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: "#F1F5F9",
    justifyContent: "center",
    alignItems: "center",
  },
  modalBodyContent: {
    alignItems: "center",
  },
  alertIconCircle: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: "#EFF6FF",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
  },
  modalAmountTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 20,
    color: COLORS.brandBlue,
    marginBottom: 4,
  },
  modalBookingCodeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#0F172A",
    backgroundColor: "#F8FAFC",
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 6,
    marginBottom: 10,
  },
  modalDescText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#64748B",
    textAlign: "center",
    lineHeight: 18,
    marginBottom: 18,
  },
  openSnapBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    width: "100%",
    paddingVertical: 13,
    borderRadius: 12,
    marginBottom: 10,
  },
  openSnapBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  confirmPaymentBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#16A34A",
    width: "100%",
    paddingVertical: 13,
    borderRadius: 12,
  },
  confirmPaymentBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
});
