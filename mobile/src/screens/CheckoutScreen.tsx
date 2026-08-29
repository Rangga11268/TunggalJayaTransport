import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Platform,
  Linking,
} from "react-native";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import { COLORS } from "../theme/colors";
import { useAuth } from "../context/AuthContext";
import { useRewards } from "../context/RewardContext";
import { useCustomAlert } from "../context/AlertContext";
import api from "../api/client";
import { ScreenHeader } from "../components/ScreenHeader";

// Modular Checkout Components
import { CheckoutTripSummary } from "../components/checkout/CheckoutTripSummary";
import { CheckoutPassengerForm } from "../components/checkout/CheckoutPassengerForm";
import { CheckoutPromoBox } from "../components/checkout/CheckoutPromoBox";
import {
  CheckoutPaymentSelector,
  PaymentMethod,
} from "../components/checkout/CheckoutPaymentSelector";
import { CheckoutPriceBreakdown } from "../components/checkout/CheckoutPriceBreakdown";
import { CheckoutMidtransModal } from "../components/checkout/CheckoutMidtransModal";

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
  const [selectedPayment, setSelectedPayment] = useState<PaymentMethod>("qris");
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
        onSuccess: (result: any) => {
          navigateToSuccess(bookingId, bookingData);
        },
        onPending: (result: any) => {
          setPendingPayment({
            bookingId,
            snapToken,
            redirectUrl,
            bookingData,
          });
          setShowPaymentModal(true);
        },
        onError: (result: any) => {
          showError("Pembayaran Gagal", "Silakan coba lagi.");
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
        <CheckoutTripSummary
          schedule={schedule}
          selectedSeats={selectedSeats}
          date={date}
        />

        {/* Passenger Form Card */}
        <CheckoutPassengerForm
          passengerName={passengerName}
          passengerPhone={passengerPhone}
          passengerEmail={passengerEmail}
          focusedField={focusedField}
          onSetName={setPassengerName}
          onSetPhone={setPassengerPhone}
          onSetEmail={setPassengerEmail}
          onSetFocusedField={setFocusedField}
        />

        {/* Promo Voucher Card */}
        <CheckoutPromoBox
          promoCode={promoCode}
          validatingPromo={validatingPromo}
          onSetPromoCode={setPromoCode}
          onApplyPromo={applyPromo}
        />

        {/* Payment Methods */}
        <CheckoutPaymentSelector
          selectedPayment={selectedPayment}
          onSelectPayment={setSelectedPayment}
        />

        {/* Payment Summary */}
        <CheckoutPriceBreakdown
          seatsCount={selectedSeats?.length || 1}
          totalPrice={totalPrice}
          discount={discount}
          finalTotal={finalTotal}
        />

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
      <CheckoutMidtransModal
        visible={showPaymentModal}
        finalTotal={finalTotal}
        pendingPayment={pendingPayment}
        verifyingPayment={verifyingPayment}
        onClose={() => setShowPaymentModal(false)}
        onOpenSnap={() =>
          triggerSnapPayment(
            pendingPayment?.snapToken!,
            pendingPayment?.redirectUrl,
            pendingPayment?.bookingId!,
            pendingPayment?.bookingData,
          )
        }
        onConfirmPayment={handleConfirmManualPayment}
      />
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
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
  },
  bottomBarWrapper: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: "#FFFFFF",
    borderTopWidth: 1,
    borderTopColor: "#E5E7EB",
    paddingHorizontal: 16,
    paddingVertical: 12,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: -4 },
        shadowOpacity: 0.08,
        shadowRadius: 10,
      },
      android: {
        elevation: 8,
      },
      web: {
        boxShadow: "0 -4px 16px rgba(0,0,0,0.06)",
      } as any,
    }),
  },
  bottomBar: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    width: "100%",
    maxWidth: 680,
    alignSelf: "center",
  },
  bottomBarLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
  },
  bottomBarPrice: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: COLORS.brandBlue,
    marginTop: 2,
  },
  payBtn: {
    backgroundColor: COLORS.brandBlue,
    borderRadius: 12,
    paddingHorizontal: 24,
    paddingVertical: 12,
    alignItems: "center",
    justifyContent: "center",
  },
  payBtnDisabled: {
    backgroundColor: "#9CA3AF",
  },
  payBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 14,
    color: "#FFFFFF",
  },
});
