import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { Ticket, Zap, Compass } from "lucide-react-native";

export const RewardHowToEarn: React.FC = () => {
  return (
    <>
      {/* 4. CARA MUDAH KUMPULKAN POIN */}
      <View style={styles.sectionBox}>
        <Text style={styles.sectionTitle}>Cara Kumpulkan Poin</Text>
        <Text style={styles.sectionSub}>
          Raih poin sebanyak-banyaknya di setiap aktivitas Anda
        </Text>

        <View style={styles.howToGrid}>
          <View style={styles.howToCard}>
            <View
              style={[styles.howToIconBox, { backgroundColor: "#EFF6FF" }]}
            >
              <Ticket size={20} color="#2563EB" />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.howToTitle}>Beli Tiket Bus AKAP</Text>
              <Text style={styles.howToDesc}>
                Dapatkan 10 Poin untuk setiap kelipatan Rp 10.000 pembelian tiket.
              </Text>
            </View>
            <View style={styles.howToBadge}>
              <Text style={styles.howToBadgeText}>+100 Poin</Text>
            </View>
          </View>

          <View style={styles.howToCard}>
            <View
              style={[styles.howToIconBox, { backgroundColor: "#FEF3C7" }]}
            >
              <Zap size={20} color="#D97706" />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.howToTitle}>Check-In Streak Harian</Text>
              <Text style={styles.howToDesc}>
                Buka aplikasi setiap hari untuk bonus poin tanpa henti.
              </Text>
            </View>
            <View style={styles.howToBadge}>
              <Text style={styles.howToBadgeText}>s.d +150 Poin</Text>
            </View>
          </View>

          <View style={styles.howToCard}>
            <View
              style={[styles.howToIconBox, { backgroundColor: "#ECFDF5" }]}
            >
              <Compass size={20} color="#059669" />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.howToTitle}>Sewa Bus Pariwisata</Text>
              <Text style={styles.howToDesc}>
                Booking sewa carter rombongan dan nikmati cashback poin besar.
              </Text>
            </View>
            <View style={styles.howToBadge}>
              <Text style={styles.howToBadgeText}>+500 Poin</Text>
            </View>
          </View>
        </View>
      </View>

      {/* 5. TIER LOYALITAS INFO */}
      <View style={styles.sectionBox}>
        <Text style={styles.sectionTitle}>Tingkatan Level VIP</Text>
        <Text style={styles.sectionSub}>
          Nikmati fasilitas ekstra seiring bertambahnya status member Anda
        </Text>

        <View style={styles.tierCardsRow}>
          <View style={[styles.tierMiniCard, { borderColor: "#E2E8F0" }]}>
            <Text style={[styles.tierMiniTitle, { color: "#64748B" }]}>
              SILVER
            </Text>
            <Text style={styles.tierMiniPoints}>0 - 1.999 Poin</Text>
            <Text style={styles.tierMiniBenefit}>
              Diskon Reguler &amp; Servis Makan
            </Text>
          </View>

          <View
            style={[
              styles.tierMiniCard,
              { borderColor: "#FDE68A", backgroundColor: "#FFFBEB" },
            ]}
          >
            <Text style={[styles.tierMiniTitle, { color: "#D97706" }]}>
              GOLD VIP
            </Text>
            <Text style={styles.tierMiniPoints}>2.000 - 4.999 Poin</Text>
            <Text style={styles.tierMiniBenefit}>
              Prioritas Kursi &amp; Ekstra Poin 1.2x
            </Text>
          </View>

          <View
            style={[
              styles.tierMiniCard,
              { borderColor: "#DDD6FE", backgroundColor: "#FAF5FF" },
            ]}
          >
            <Text style={[styles.tierMiniTitle, { color: "#7C3AED" }]}>
              PLATINUM
            </Text>
            <Text style={styles.tierMiniPoints}>5.000+ Poin</Text>
            <Text style={styles.tierMiniBenefit}>
              Free Snack &amp; Bebas Reschedule
            </Text>
          </View>
        </View>
      </View>
    </>
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
  howToGrid: {
    gap: 10,
    marginTop: 14,
  },
  howToCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F9FAFB",
    borderRadius: 14,
    padding: 12,
    borderWidth: 1,
    borderColor: "#E5E7EB",
  },
  howToIconBox: {
    width: 38,
    height: 38,
    borderRadius: 10,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  howToTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#111827",
  },
  howToDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#6B7280",
    marginTop: 2,
  },
  howToBadge: {
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 8,
    marginLeft: 8,
  },
  howToBadgeText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 10,
    color: "#2563EB",
  },
  tierCardsRow: {
    flexDirection: "row",
    gap: 8,
    marginTop: 14,
  },
  tierMiniCard: {
    flex: 1,
    borderRadius: 12,
    padding: 10,
    borderWidth: 1,
    backgroundColor: "#F9FAFB",
  },
  tierMiniTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 10.5,
    marginBottom: 4,
  },
  tierMiniPoints: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#111827",
  },
  tierMiniBenefit: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 8.5,
    color: "#6B7280",
    marginTop: 4,
    lineHeight: 12,
  },
});
