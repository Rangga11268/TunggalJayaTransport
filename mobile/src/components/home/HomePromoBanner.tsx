import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
} from "react-native";
import { Tag, Copy, Check } from "lucide-react-native";

interface HomePromoBannerProps {
  couponCopied: boolean;
  onCopyCoupon: () => void;
}

export const HomePromoBanner: React.FC<HomePromoBannerProps> = ({
  couponCopied,
  onCopyCoupon,
}) => {
  return (
    <View style={styles.flashPromoBanner}>
      <View style={styles.flashPromoLeft}>
        <View style={styles.promoTagCircle}>
          <Tag size={16} color="#2563EB" />
        </View>
        <View style={{ flex: 1, marginLeft: 10 }}>
          <Text style={styles.promoBannerTitle}>
            Diskon 10% Semua Rute AKAP
          </Text>
          <Text style={styles.promoBannerSub}>
            Kode Voucher: <Text style={styles.promoCodeText}>TJBERKAH</Text>
          </Text>
        </View>
      </View>

      <TouchableOpacity
        activeOpacity={0.75}
        onPress={onCopyCoupon}
        style={[
          styles.copyCouponBtn,
          couponCopied && styles.copyCouponBtnActive,
        ]}
      >
        {couponCopied ? (
          <>
            <Check size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
            <Text style={styles.copyCouponTextActive}>Tersalin</Text>
          </>
        ) : (
          <>
            <Copy size={12} color="#2563EB" style={{ marginRight: 4 }} />
            <Text style={styles.copyCouponText}>Salin</Text>
          </>
        )}
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  flashPromoBanner: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    borderRadius: 16,
    paddingHorizontal: 14,
    paddingVertical: 12,
    marginBottom: 20,
  },
  flashPromoLeft: {
    flexDirection: "row",
    alignItems: "center",
    flex: 1,
  },
  promoTagCircle: {
    width: 34,
    height: 34,
    borderRadius: 17,
    backgroundColor: "#DBEAFE",
    justifyContent: "center",
    alignItems: "center",
  },
  promoBannerTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#1E3A8A",
  },
  promoBannerSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#3B82F6",
    marginTop: 2,
  },
  promoCodeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    color: "#1D4ED8",
    letterSpacing: 0.5,
  },
  copyCouponBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#93C5FD",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
    marginLeft: 8,
  },
  copyCouponBtnActive: {
    backgroundColor: "#16A34A",
    borderColor: "#16A34A",
  },
  copyCouponText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#2563EB",
  },
  copyCouponTextActive: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11.5,
    color: "#FFFFFF",
  },
});
