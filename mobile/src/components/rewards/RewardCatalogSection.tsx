import React from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  StyleSheet,
} from "react-native";
import { Sparkles } from "lucide-react-native";
import { RewardItem } from "../../context/RewardContext";
import {
  PromoVoucherIcon,
  FreeMealBuffetIcon,
  ExecutiveLeatherSeatsIcon,
  RewardsLoyaltyIcon,
} from "../ServiceIcons";
import { COLORS } from "../../theme/colors";

interface RewardCatalogSectionProps {
  selectedCategory: string;
  filteredCatalog: RewardItem[];
  points: number;
  onSelectCategory: (cat: "all" | "voucher" | "snack" | "merchandise" | "seat") => void;
  onRedeemItem: (item: RewardItem) => void;
}

export const RewardCatalogSection: React.FC<RewardCatalogSectionProps> = ({
  selectedCategory,
  filteredCatalog,
  points,
  onSelectCategory,
  onRedeemItem,
}) => {
  return (
    <View style={styles.sectionBox}>
      <Text style={styles.sectionTitle}>Tukar Hadiah &amp; Voucher</Text>
      <Text style={styles.sectionSub}>
        Gunakan poin Anda untuk mendapatkan potongan tiket &amp; fasilitas istimewa
      </Text>

      {/* Category Filter Chips */}
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={styles.categoryChipsRow}
      >
        {[
          { id: "all", label: "Semua Hadiah" },
          { id: "voucher", label: "Voucher Tiket" },
          { id: "snack", label: "Snack & Minum" },
          { id: "seat", label: "Kursi VIP" },
          { id: "merchandise", label: "Merchandise" },
        ].map((cat) => (
          <TouchableOpacity
            key={cat.id}
            onPress={() => onSelectCategory(cat.id as any)}
            style={[
              styles.catChip,
              selectedCategory === cat.id && styles.catChipActive,
            ]}
          >
            <Text
              style={[
                styles.catChipText,
                selectedCategory === cat.id && styles.catChipTextActive,
              ]}
            >
              {cat.label}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Catalog Items */}
      <View style={styles.catalogGrid}>
        {filteredCatalog.map((item) => {
          const canRedeem = points >= item.pointsRequired;
          return (
            <View key={item.id} style={styles.catalogCard}>
              <View style={styles.catalogCardLeft}>
                <View style={styles.catalogIconBox}>
                  {item.category === "voucher" && (
                    <PromoVoucherIcon size={42} />
                  )}
                  {item.category === "snack" && (
                    <FreeMealBuffetIcon size={42} />
                  )}
                  {item.category === "seat" && (
                    <ExecutiveLeatherSeatsIcon size={42} />
                  )}
                  {item.category === "merchandise" && (
                    <RewardsLoyaltyIcon size={42} />
                  )}
                </View>
                <View style={{ flex: 1 }}>
                  <Text style={styles.catalogTitle}>{item.title}</Text>
                  <Text style={styles.catalogDesc}>{item.description}</Text>
                  <View style={styles.pointsCostRow}>
                    <Sparkles size={13} color="#D97706" />
                    <Text style={styles.pointsCostText}>
                      {item.pointsRequired.toLocaleString("id-ID")} Poin
                    </Text>
                  </View>
                </View>
              </View>

              <TouchableOpacity
                style={[
                  styles.redeemBtn,
                  !canRedeem && styles.redeemBtnDisabled,
                ]}
                onPress={() => onRedeemItem(item)}
                activeOpacity={0.85}
              >
                <Text
                  style={[
                    styles.redeemBtnText,
                    !canRedeem && styles.redeemBtnTextDisabled,
                  ]}
                >
                  {canRedeem ? "Tukar" : "Poin Kurang"}
                </Text>
              </TouchableOpacity>
            </View>
          );
        })}
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  sectionBox: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    marginBottom: 20,
  },
  sectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
  },
  sectionSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  categoryChipsRow: {
    gap: 8,
    marginVertical: 14,
  },
  catChip: {
    backgroundColor: "#F3F4F6",
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: 10,
  },
  catChipActive: {
    backgroundColor: COLORS.brandBlue,
  },
  catChipText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#4B5563",
  },
  catChipTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  catalogGrid: {
    gap: 12,
  },
  catalogCard: {
    backgroundColor: "#F9FAFB",
    borderRadius: 14,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E5E7EB",
  },
  catalogCardLeft: {
    flexDirection: "row",
    gap: 12,
    marginBottom: 10,
  },
  catalogIconBox: {
    width: 48,
    height: 48,
    borderRadius: 12,
    backgroundColor: "#EFF6FF",
    justifyContent: "center",
    alignItems: "center",
  },
  catalogTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  catalogDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    marginTop: 2,
  },
  pointsCostRow: {
    flexDirection: "row",
    alignItems: "center",
    gap: 4,
    marginTop: 6,
  },
  pointsCostText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 12,
    color: "#D97706",
  },
  redeemBtn: {
    backgroundColor: COLORS.brandBlue,
    borderRadius: 10,
    paddingVertical: 9,
    alignItems: "center",
    justifyContent: "center",
  },
  redeemBtnDisabled: {
    backgroundColor: "#E5E7EB",
  },
  redeemBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
  redeemBtnTextDisabled: {
    color: "#9CA3AF",
  },
});
