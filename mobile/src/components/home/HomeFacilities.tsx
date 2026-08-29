import React from "react";
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  Platform,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { SectionHeader } from "../SectionHeader";
import {
  AirSuspensionIcon,
  FreeMealBuffetIcon,
  FastChargingIcon,
  FreeCoffeeIcon,
} from "../ServiceIcons";

export const HomeFacilities: React.FC = () => {
  return (
    <View style={styles.featuresSection}>
      <SectionHeader
        title="Keunggulan Layanan"
        subtitle="Standar Kenyamanan PO Tunggal Jaya"
        style={{ marginBottom: 12 }}
      />

      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={styles.featuresScroll}
      >
        {/* Card 1: Suspensi Udara */}
        <LinearGradient
          colors={["#F0F7FF", "#FFFFFF"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={[styles.featureCard, { borderColor: "#BAE6FD" }]}
        >
          <View style={styles.featureTopRow}>
            <View
              style={[
                styles.featureSvgWrapper,
                { backgroundColor: "#E0F2FE" },
              ]}
            >
              <AirSuspensionIcon size={38} />
            </View>
            <View
              style={[styles.microBadge, { backgroundColor: "#E0F2FE" }]}
            >
              <Text style={[styles.microBadgeText, { color: "#0284C7" }]}>
                Hino RM 280
              </Text>
            </View>
          </View>
          <Text style={styles.featureTitle}>Suspensi Udara</Text>
          <Text style={styles.featureDesc}>
            Air suspension empuk &amp; stabil melaju di Tol Cipali.
          </Text>
        </LinearGradient>

        {/* Card 2: Makan Prasmanan */}
        <LinearGradient
          colors={["#F0FDF4", "#FFFFFF"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={[styles.featureCard, { borderColor: "#BBF7D0" }]}
        >
          <View style={styles.featureTopRow}>
            <View
              style={[
                styles.featureSvgWrapper,
                { backgroundColor: "#DCFCE7" },
              ]}
            >
              <FreeMealBuffetIcon size={38} />
            </View>
            <View
              style={[styles.microBadge, { backgroundColor: "#DCFCE7" }]}
            >
              <Text style={[styles.microBadgeText, { color: "#16A34A" }]}>
                KM 166
              </Text>
            </View>
          </View>
          <Text style={styles.featureTitle}>Makan Prasmanan</Text>
          <Text style={styles.featureDesc}>
            Gratis servis makan prasmanan di Rest Area KM 166.
          </Text>
        </LinearGradient>

        {/* Card 3: USB Fast Charging */}
        <LinearGradient
          colors={["#FFFBEB", "#FFFFFF"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={[styles.featureCard, { borderColor: "#FDE68A" }]}
        >
          <View style={styles.featureTopRow}>
            <View
              style={[
                styles.featureSvgWrapper,
                { backgroundColor: "#FEF3C7" },
              ]}
            >
              <FastChargingIcon size={38} />
            </View>
            <View
              style={[styles.microBadge, { backgroundColor: "#FEF3C7" }]}
            >
              <Text style={[styles.microBadgeText, { color: "#D97706" }]}>
                Fast 18W
              </Text>
            </View>
          </View>
          <Text style={styles.featureTitle}>USB Fast Charging</Text>
          <Text style={styles.featureDesc}>
            Port charger HP di setiap bangku selama perjalanan.
          </Text>
        </LinearGradient>

        {/* Card 4: Kopi & Air Mineral */}
        <LinearGradient
          colors={["#FAF5FF", "#FFFFFF"]}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={[styles.featureCard, { borderColor: "#DDD6FE" }]}
        >
          <View style={styles.featureTopRow}>
            <View
              style={[
                styles.featureSvgWrapper,
                { backgroundColor: "#F3E8FF" },
              ]}
            >
              <FreeCoffeeIcon size={38} />
            </View>
            <View
              style={[styles.microBadge, { backgroundColor: "#F3E8FF" }]}
            >
              <Text style={[styles.microBadgeText, { color: "#9333EA" }]}>
                Gratis
              </Text>
            </View>
          </View>
          <Text style={styles.featureTitle}>Kopi &amp; Air Mineral</Text>
          <Text style={styles.featureDesc}>
            Fasilitas kopi &amp; air mineral gratis di perjalanan.
          </Text>
        </LinearGradient>
      </ScrollView>
    </View>
  );
};

const styles = StyleSheet.create({
  featuresSection: {
    marginBottom: 24,
  },
  featuresScroll: {
    gap: 12,
    paddingRight: 16,
  },
  featureCard: {
    width: 175,
    borderRadius: 18,
    padding: 14,
    borderWidth: 1,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.04,
        shadowRadius: 8,
      },
      android: {
        elevation: 2,
      },
      web: {
        boxShadow: "0 2px 8px rgba(15, 23, 42, 0.04)",
      } as any,
    }),
  },
  featureTopRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-start",
    marginBottom: 10,
  },
  featureSvgWrapper: {
    width: 46,
    height: 46,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
  },
  microBadge: {
    paddingHorizontal: 6,
    paddingVertical: 3,
    borderRadius: 6,
  },
  microBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
  },
  featureTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#0F172A",
    marginBottom: 4,
  },
  featureDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#64748B",
    lineHeight: 15,
  },
});
