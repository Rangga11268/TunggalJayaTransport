import React from "react";
import { View, Text, TouchableOpacity, StyleSheet } from "react-native";
import {
  OfficialQrisBrandIcon,
  OfficialBankVaBrandIcon,
  OfficialEwalletBrandIcon,
} from "../ServiceIcons";
import { COLORS } from "../../theme/colors";

export type PaymentMethod = "qris" | "bank_transfer" | "gopay" | "shopeepay";

interface CheckoutPaymentSelectorProps {
  selectedPayment: PaymentMethod;
  onSelectPayment: (method: PaymentMethod) => void;
}

export const CheckoutPaymentSelector: React.FC<
  CheckoutPaymentSelectorProps
> = ({ selectedPayment, onSelectPayment }) => {
  return (
    <View style={styles.card}>
      <Text style={styles.cardSectionTitle}>Metode Pembayaran</Text>

      {/* 1. QRIS */}
      <TouchableOpacity
        activeOpacity={0.8}
        onPress={() => onSelectPayment("qris")}
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
        onPress={() => onSelectPayment("bank_transfer")}
        style={[
          styles.paymentOption,
          selectedPayment === "bank_transfer" && styles.paymentOptionSelected,
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
        onPress={() => onSelectPayment("gopay")}
        style={[
          styles.paymentOption,
          selectedPayment === "gopay" && styles.paymentOptionSelected,
        ]}
      >
        <View style={styles.paymentLeft}>
          <OfficialEwalletBrandIcon size={42} />
          <View style={{ flex: 1 }}>
            <Text style={styles.paymentTitle}>GoPay / ShopeePay E-Wallet</Text>
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
    marginBottom: 14,
  },
  paymentOption: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#F9FAFB",
    borderWidth: 1.5,
    borderColor: "#E5E7EB",
    borderRadius: 14,
    padding: 12,
    marginBottom: 10,
  },
  paymentOptionSelected: {
    borderColor: COLORS.brandBlue,
    backgroundColor: "#EFF6FF",
  },
  paymentLeft: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
    flex: 1,
  },
  paymentTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#111827",
  },
  paymentSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#6B7280",
    marginTop: 2,
  },
  radioCircle: {
    width: 18,
    height: 18,
    borderRadius: 9,
    borderWidth: 2,
    borderColor: "#D1D5DB",
    marginLeft: 8,
  },
  radioCircleActive: {
    borderColor: COLORS.brandBlue,
    backgroundColor: COLORS.brandBlue,
  },
});
