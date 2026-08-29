import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet,
  Platform,
} from "react-native";
import { MessageCircle } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

interface CharterSummaryBoxProps {
  daysCount: number;
  busCount: number;
  estimatedTotal: number;
  loading: boolean;
  onSubmit: () => void;
  onSendWhatsApp: () => void;
}

export const CharterSummaryBox: React.FC<CharterSummaryBoxProps> = ({
  daysCount,
  busCount,
  estimatedTotal,
  loading,
  onSubmit,
  onSendWhatsApp,
}) => {
  return (
    <View style={styles.bottomBar}>
      <View style={styles.bottomPriceCol}>
        <Text style={styles.bottomPriceLabel}>
          Estimasi ({daysCount} Hari x {busCount} Unit)
        </Text>
        <Text style={styles.bottomPriceValue}>
          Rp {estimatedTotal.toLocaleString("id-ID")}
        </Text>
      </View>

      <View style={styles.bottomActionRow}>
        <TouchableOpacity
          activeOpacity={0.85}
          onPress={onSubmit}
          disabled={loading}
          style={styles.bookBtn}
        >
          {loading ? (
            <ActivityIndicator color="#FFFFFF" />
          ) : (
            <Text style={styles.bookBtnText}>Ajukan Sewa</Text>
          )}
        </TouchableOpacity>

        <TouchableOpacity
          activeOpacity={0.85}
          onPress={onSendWhatsApp}
          style={styles.waQuickBtn}
        >
          <MessageCircle size={18} color="#FFFFFF" />
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  bottomBar: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: "#FFFFFF",
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 14,
    borderTopWidth: 1,
    borderTopColor: "#E5E7EB",
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
  bottomPriceCol: {
    flex: 1,
  },
  bottomPriceLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
  },
  bottomPriceValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 18,
    color: COLORS.brandBlue,
    marginTop: 2,
  },
  bottomActionRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 8,
  },
  bookBtn: {
    backgroundColor: COLORS.brandBlue,
    paddingHorizontal: 18,
    paddingVertical: 12,
    borderRadius: 12,
    alignItems: "center",
    justifyContent: "center",
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandBlue,
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.25,
        shadowRadius: 8,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  bookBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  waQuickBtn: {
    backgroundColor: "#059669",
    width: 44,
    height: 44,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    ...Platform.select({
      ios: {
        shadowColor: "#059669",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.25,
        shadowRadius: 8,
      },
      android: {
        elevation: 4,
      },
    }),
  },
});
