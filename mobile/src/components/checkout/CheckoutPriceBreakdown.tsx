import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { ShieldCheck } from "lucide-react-native";

interface CheckoutPriceBreakdownProps {
  seatsCount: number;
  totalPrice: number;
  discount: number;
  finalTotal: number;
}

export const CheckoutPriceBreakdown: React.FC<CheckoutPriceBreakdownProps> = ({
  seatsCount,
  totalPrice,
  discount,
  finalTotal,
}) => {
  return (
    <>
      <View style={styles.card}>
        <Text style={styles.cardSectionTitle}>Rincian Tarif</Text>
        <View style={styles.summaryLine}>
          <Text style={styles.summaryLabel}>Harga Tiket ({seatsCount}x)</Text>
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
    </>
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
    marginBottom: 12,
  },
  summaryLine: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 8,
  },
  summaryLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#6B7280",
  },
  summaryVal: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#111827",
  },
  summaryDivider: {
    height: 1,
    backgroundColor: "#F3F4F6",
    marginVertical: 8,
  },
  totalLabel: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#111827",
  },
  totalVal: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#2563EB",
  },
  sslBadge: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#ECFDF5",
    borderWidth: 1,
    borderColor: "#A7F3D0",
    borderRadius: 12,
    paddingVertical: 10,
    paddingHorizontal: 12,
    gap: 8,
    marginBottom: 16,
  },
  sslText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#065F46",
    flex: 1,
  },
});
