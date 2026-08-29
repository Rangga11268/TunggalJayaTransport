import React from "react";
import {
  View,
  Text,
  Modal,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet,
} from "react-native";
import { CreditCard, ExternalLink, CheckCircle2, X } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface CheckoutMidtransModalProps {
  visible: boolean;
  finalTotal: number;
  pendingPayment: any;
  verifyingPayment: boolean;
  onClose: () => void;
  onOpenSnap: () => void;
  onConfirmPayment: () => void;
}

export const CheckoutMidtransModal: React.FC<CheckoutMidtransModalProps> = ({
  visible,
  finalTotal,
  pendingPayment,
  verifyingPayment,
  onClose,
  onOpenSnap,
  onConfirmPayment,
}) => {
  return (
    <Modal
      visible={visible}
      transparent={true}
      animationType="fade"
      onRequestClose={onClose}
    >
      <View style={styles.modalOverlay}>
        <View style={styles.paymentModalBox}>
          <View style={styles.modalHeaderRow}>
            <Text style={styles.modalHeaderTitle}>Selesaikan Pembayaran</Text>
            <TouchableOpacity onPress={onClose} style={styles.modalCloseBtn}>
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
                onPress={onOpenSnap}
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
              onPress={onConfirmPayment}
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
  );
};

const styles = StyleSheet.create({
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(0, 0, 0, 0.55)",
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  paymentModalBox: {
    backgroundColor: "#FFFFFF",
    borderRadius: 22,
    width: "100%",
    maxWidth: 380,
    padding: 20,
  },
  modalHeaderRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#E5E7EB",
  },
  modalHeaderTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#111827",
  },
  modalCloseBtn: {
    padding: 4,
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
    color: "#111827",
    marginBottom: 4,
  },
  modalBookingCodeText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 12,
    color: COLORS.brandBlue,
    marginBottom: 10,
  },
  modalDescText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#6B7280",
    textAlign: "center",
    lineHeight: 17,
    marginBottom: 20,
  },
  openSnapBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: COLORS.brandBlue,
    borderRadius: 12,
    paddingVertical: 12,
    width: "100%",
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
    borderRadius: 12,
    paddingVertical: 12,
    width: "100%",
  },
  confirmPaymentBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
});
