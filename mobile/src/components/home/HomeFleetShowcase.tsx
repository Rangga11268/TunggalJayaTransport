import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Platform,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { Sparkles, ArrowRight } from "lucide-react-native";

interface HomeFleetShowcaseProps {
  onPress: () => void;
}

export const HomeFleetShowcase: React.FC<HomeFleetShowcaseProps> = ({
  onPress,
}) => {
  return (
    <TouchableOpacity
      activeOpacity={0.9}
      onPress={onPress}
      style={styles.heroBannerCard}
    >
      <Image
        source={require("../../../assets/images/resiBisma.webp")}
        style={styles.heroBannerBg}
        resizeMode="cover"
      />
      <LinearGradient
        colors={["rgba(15, 23, 42, 0.2)", "rgba(15, 23, 42, 0.9)"]}
        style={styles.heroBannerOverlay}
      >
        <View style={styles.heroBadgesRow}>
          <View style={styles.heroGlassBadge}>
            <Sparkles size={11} color="#38BDF8" style={{ marginRight: 4 }} />
            <Text style={styles.heroGlassBadgeText}>
              Adiputro SHD Single Glass
            </Text>
          </View>
          <View style={styles.heroGlassBadge}>
            <Text style={styles.heroGlassBadgeText}>Air Suspension</Text>
          </View>
        </View>

        <View style={styles.heroContentBottom}>
          <View style={{ flex: 1 }}>
            <Text style={styles.heroBannerHeading}>
              Jetbus 5 Super High Deck
            </Text>
            <Text style={styles.heroBannerSub}>
              Armada Hino RM 280 • Full AC &amp; Reclining Seat
            </Text>
          </View>

          <View style={styles.heroActionBtn}>
            <Text style={styles.heroActionText}>Pesan</Text>
            <ArrowRight size={14} color="#FFFFFF" style={{ marginLeft: 4 }} />
          </View>
        </View>
      </LinearGradient>
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  heroBannerCard: {
    height: 180,
    borderRadius: 22,
    overflow: "hidden",
    marginBottom: 24,
    position: "relative",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.12,
        shadowRadius: 14,
      },
      android: {
        elevation: 4,
      },
      web: {
        boxShadow: "0 6px 20px rgba(15, 23, 42, 0.08)",
      } as any,
    }),
  },
  heroBannerBg: {
    width: "100%",
    height: "100%",
    position: "absolute",
  },
  heroBannerOverlay: {
    flex: 1,
    padding: 16,
    justifyContent: "space-between",
  },
  heroBadgesRow: {
    flexDirection: "row",
    gap: 8,
  },
  heroGlassBadge: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(15, 23, 42, 0.6)",
    borderWidth: 1,
    borderColor: "rgba(255, 255, 255, 0.2)",
    paddingHorizontal: 9,
    paddingVertical: 4,
    borderRadius: 12,
  },
  heroGlassBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#FFFFFF",
  },
  heroContentBottom: {
    flexDirection: "row",
    alignItems: "flex-end",
    justifyContent: "space-between",
  },
  heroBannerHeading: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#FFFFFF",
  },
  heroBannerSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#E2E8F0",
    marginTop: 2,
  },
  heroActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#2563EB",
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: 10,
  },
  heroActionText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#FFFFFF",
  },
});
