import React from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Image,
  StyleSheet,
} from "react-native";
import { LinearGradient } from "expo-linear-gradient";
import { SectionHeader } from "../SectionHeader";

interface HomeStoriesSectionProps {
  onNavigatePromo: () => void;
  onNavigateSchedules: () => void;
  onNavigateCharter: () => void;
}

export const HomeStoriesSection: React.FC<HomeStoriesSectionProps> = ({
  onNavigatePromo,
  onNavigateSchedules,
  onNavigateCharter,
}) => {
  return (
    <>
      <SectionHeader
        title="Kabar &amp; Cerita"
        actionLabel="Lihat Semua >"
        onAction={onNavigatePromo}
      />

      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={styles.whatsNewScroll}
      >
        <TouchableOpacity
          activeOpacity={0.85}
          onPress={onNavigateSchedules}
          style={styles.storyCard}
        >
          <Image
            source={require("../../../assets/images/bentas01.webp")}
            style={styles.storyImage}
            resizeMode="cover"
          />
          <LinearGradient
            colors={["transparent", "rgba(17, 24, 39, 0.88)"]}
            style={styles.storyGradient}
          >
            <Text style={styles.storyTitle}>Bentas-01 Kuningan - Jkt</Text>
            <Text style={styles.storySubtitle}>
              Jadwal harian rute favorit via Tol Cipali
            </Text>
            <View style={styles.storyPill}>
              <Text style={styles.storyPillText}>Cek Jadwal &gt;</Text>
            </View>
          </LinearGradient>
        </TouchableOpacity>

        <TouchableOpacity
          activeOpacity={0.85}
          onPress={onNavigateCharter}
          style={styles.storyCard}
        >
          <Image
            source={require("../../../assets/images/kylorenParwis.webp")}
            style={styles.storyImage}
            resizeMode="cover"
          />
          <LinearGradient
            colors={["transparent", "rgba(17, 24, 39, 0.88)"]}
            style={styles.storyGradient}
          >
            <Text style={styles.storyTitle}>Kylo Ren Jetbus 5 SHD</Text>
            <Text style={styles.storySubtitle}>
              Armada pariwisata Hino RM 280
            </Text>
            <View style={styles.storyPill}>
              <Text style={styles.storyPillText}>Sewa Unit &gt;</Text>
            </View>
          </LinearGradient>
        </TouchableOpacity>
      </ScrollView>
    </>
  );
};

const styles = StyleSheet.create({
  whatsNewScroll: {
    gap: 14,
    paddingRight: 16,
    marginBottom: 24,
  },
  storyCard: {
    width: 220,
    height: 145,
    borderRadius: 18,
    overflow: "hidden",
    position: "relative",
  },
  storyImage: {
    width: "100%",
    height: "100%",
    position: "absolute",
  },
  storyGradient: {
    flex: 1,
    padding: 12,
    justifyContent: "flex-end",
  },
  storyTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  storySubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#CBD5E1",
    marginTop: 2,
  },
  storyPill: {
    marginTop: 6,
    alignSelf: "flex-start",
    backgroundColor: "rgba(255, 255, 255, 0.2)",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  storyPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#FFFFFF",
  },
});
