import React from "react";
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet,
} from "react-native";
import { COLORS } from "../../theme/colors";

interface CheckoutPromoBoxProps {
  promoCode: string;
  validatingPromo: boolean;
  onSetPromoCode: (v: string) => void;
  onApplyPromo: () => void;
}

export const CheckoutPromoBox: React.FC<CheckoutPromoBoxProps> = ({
  promoCode,
  validatingPromo,
  onSetPromoCode,
  onApplyPromo,
}) => {
  return (
    <View style={styles.card}>
      <Text style={styles.cardSectionTitle}>Kupon &amp; Voucher Diskon</Text>
      <View style={styles.promoInputRow}>
        <TextInput
          style={styles.promoInput}
          placeholder="Masukkan kode promo (TJBERKAH)"
          placeholderTextColor="#9CA3AF"
          autoCapitalize="characters"
          value={promoCode}
          onChangeText={onSetPromoCode}
        />
        <TouchableOpacity
          style={styles.promoApplyBtn}
          onPress={onApplyPromo}
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
  promoInputRow: {
    flexDirection: "row",
    gap: 8,
  },
  promoInput: {
    flex: 1,
    backgroundColor: "#F9FAFB",
    borderWidth: 1,
    borderColor: "#E5E7EB",
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 44,
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#111827",
    letterSpacing: 0.5,
  },
  promoApplyBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 16,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
  },
  promoApplyText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#FFFFFF",
  },
});
