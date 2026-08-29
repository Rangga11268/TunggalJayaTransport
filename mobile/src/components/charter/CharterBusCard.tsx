import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Platform,
} from "react-native";
import { Check } from "lucide-react-native";
import { COLORS } from "../../theme/colors";

export interface CharterBusOption {
  id: string;
  title: string;
  seats: string;
  badge: string;
  badgeColor: string;
  desc: string;
  pricePerDay: number;
  image: any;
  features: string[];
}

// Official Charter Bus Types from Tunggal Jaya Web Profile
export const CHARTER_BUS_OPTIONS: CharterBusOption[] = [
  {
    id: "bigbus-50",
    title: "Bigbus Executive",
    seats: "50 Seat (2-2)",
    badge: "Armada Viral (Kylo Ren)",
    badgeColor: "#2563EB",
    desc: "Unit ikonik Kylo Ren, Bentas & Kids Panda dengan suspensi udara empuk.",
    pricePerDay: 3800000,
    image: require("../../../assets/images/kylorenParwis.webp"),
    features: [
      "Air Suspension Empuk",
      "Audio Karaoke & Disco Light",
      "Smart Android TV",
      "USB Fast Charger",
      "Dispenser & Bagasi Luas",
    ],
  },
  {
    id: "bigbus-59",
    title: "Bigbus Max Capacity",
    seats: "59 Seat (2-3)",
    badge: "Paling Hemat (Study Tour)",
    badgeColor: "#059669",
    desc: "Kapasitas muatan terbesar untuk rombongan study tour sekolah & instansi.",
    pricePerDay: 3500000,
    image: require("../../../assets/images/resiBisma.webp"),
    features: [
      "Full AC Dingin Merata",
      "Reclining Seat 2-3 Nyaman",
      "Audio Music System",
      "Bagasi Ekstra Kapasitas",
      "Kru Berpengalaman",
    ],
  },
  {
    id: "bigbus-custom",
    title: "Bigbus Seat Custom / Legrest",
    seats: "Seat Custom (Legrest 40-45)",
    badge: "Eksklusif VIP Long Trip",
    badgeColor: "#D97706",
    desc: "Konfigurasi kursi santai fleksibel & sandaran kaki legrest untuk kenyamanan maksimal.",
    pricePerDay: 4200000,
    image: require("../../../assets/images/primadona.webp"),
    features: [
      "Pneumatic Air Suspension",
      "Legrest Seat Santai",
      "Audio Karaoke System",
      "Smart TV & USB Port",
      "Toilet Bersih Terawat",
    ],
  },
];

interface CharterBusCardProps {
  bus: CharterBusOption;
  isSelected: boolean;
  onSelect: () => void;
}

export const CharterBusCard: React.FC<CharterBusCardProps> = ({
  bus,
  isSelected,
  onSelect,
}) => {
  return (
    <TouchableOpacity
      activeOpacity={0.88}
      onPress={onSelect}
      style={[styles.busCard, isSelected && styles.busCardSelected]}
    >
      <Image
        source={bus.image}
        style={styles.busCardImage}
        resizeMode="cover"
      />

      <View style={styles.busCardBody}>
        <View style={styles.busCardHeader}>
          <View style={{ flex: 1 }}>
            <Text style={styles.busCardTitle}>{bus.title}</Text>
            <Text style={styles.busCardSeats}>{bus.seats}</Text>
          </View>
          <View
            style={[
              styles.categoryBadge,
              { backgroundColor: `${bus.badgeColor}15` },
            ]}
          >
            <Text
              style={[
                styles.categoryBadgeText,
                { color: bus.badgeColor },
              ]}
            >
              {bus.badge}
            </Text>
          </View>
        </View>

        <Text style={styles.busCardDesc}>{bus.desc}</Text>

        {/* Feature Chips */}
        <View style={styles.featuresRow}>
          {bus.features.slice(0, 3).map((feat, idx) => (
            <View key={idx} style={styles.featurePill}>
              <Check
                size={11}
                color={COLORS.brandBlue}
                style={{ marginRight: 4 }}
              />
              <Text style={styles.featurePillText}>{feat}</Text>
            </View>
          ))}
        </View>

        <View style={styles.busCardFooter}>
          <View>
            <Text style={styles.priceLabel}>
              Estimasi Sewa / Hari
            </Text>
            <Text style={styles.priceValue}>
              Rp {bus.pricePerDay.toLocaleString("id-ID")}
            </Text>
          </View>

          <View
            style={[
              styles.selectRadio,
              isSelected && styles.selectRadioActive,
            ]}
          >
            {isSelected && <View style={styles.selectRadioDot} />}
          </View>
        </View>
      </View>
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  busCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    overflow: "hidden",
    borderWidth: 1.5,
    borderColor: "#E5E7EB",
    marginBottom: 14,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
      web: {
        boxShadow: "0 4px 12px rgba(15, 23, 42, 0.04)",
      } as any,
    }),
  },
  busCardSelected: {
    borderColor: COLORS.brandBlue,
    borderWidth: 2,
    backgroundColor: "#F8FAFF",
  },
  busCardImage: {
    width: "100%",
    height: 140,
  },
  busCardBody: {
    padding: 14,
  },
  busCardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-start",
    marginBottom: 6,
  },
  busCardTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14.5,
    color: "#111827",
  },
  busCardSeats: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: COLORS.brandBlue,
    marginTop: 2,
  },
  categoryBadge: {
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  categoryBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9.5,
  },
  busCardDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#6B7280",
    lineHeight: 16,
    marginBottom: 10,
  },
  featuresRow: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 6,
    marginBottom: 12,
  },
  featurePill: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 7,
    paddingVertical: 3,
    borderRadius: 6,
  },
  featurePillText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 10,
    color: "#1E40AF",
  },
  busCardFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    borderTopWidth: 1,
    borderTopColor: "#F3F4F6",
    paddingTop: 10,
  },
  priceLabel: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#9CA3AF",
  },
  priceValue: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: COLORS.brandBlue,
  },
  selectRadio: {
    width: 20,
    height: 20,
    borderRadius: 10,
    borderWidth: 2,
    borderColor: "#D1D5DB",
    justifyContent: "center",
    alignItems: "center",
  },
  selectRadioActive: {
    borderColor: COLORS.brandBlue,
  },
  selectRadioDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: COLORS.brandBlue,
  },
});
