import React from "react";
import { View, Text, ScrollView, StyleSheet } from "react-native";
import { ShieldCheck, Tv, Wifi, Coffee, Check } from "lucide-react-native";
import { SectionHeader } from "../SectionHeader";
import { COLORS } from "../../theme/colors";

export const CharterAmenities: React.FC = () => {
  return (
    <>
      {/* Official Facilities Card */}
      <View style={styles.amenitiesCard}>
        <Text style={styles.amenitiesTitle}>
          Standar Fasilitas Pariwisata PO Tunggal Jaya
        </Text>
        <View style={styles.amenitiesGrid}>
          <View style={styles.amenityItem}>
            <ShieldCheck size={18} color={COLORS.brandBlue} />
            <Text style={styles.amenityText}>Kru Driver Berlisensi</Text>
          </View>
          <View style={styles.amenityItem}>
            <Tv size={18} color={COLORS.brandBlue} />
            <Text style={styles.amenityText}>Full Audio &amp; Karaoke</Text>
          </View>
          <View style={styles.amenityItem}>
            <Wifi size={18} color={COLORS.brandBlue} />
            <Text style={styles.amenityText}>Air Suspension Nyaman</Text>
          </View>
          <View style={styles.amenityItem}>
            <Coffee size={18} color={COLORS.brandBlue} />
            <Text style={styles.amenityText}>Dispenser &amp; Cooler</Text>
          </View>
        </View>
      </View>

      {/* Destinasi Wisata Populer */}
      <View style={styles.sectionBox}>
        <SectionHeader
          title="Destinasi Wisata Favorit"
          subtitle="Rute Populer Rombongan Pariwisata"
          style={{ marginBottom: 12 }}
        />

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.destScroll}
        >
          <View style={styles.destCard}>
            <Text style={styles.destCity}>YOGYAKARTA</Text>
            <Text style={styles.destTitle}>
              Malioboro &amp; Candi Borobudur
            </Text>
            <Text style={styles.destSub}>
              Paket 3H2M • Free Tol &amp; Parkir Wisata
            </Text>
          </View>

          <View style={styles.destCard}>
            <Text style={[styles.destCity, { color: "#059669" }]}>BANDUNG</Text>
            <Text style={styles.destTitle}>Lembang &amp; Ciwidey Tour</Text>
            <Text style={styles.destSub}>
              Paket 2H1M • Rute Fleksibel Acara
            </Text>
          </View>

          <View style={styles.destCard}>
            <Text style={[styles.destCity, { color: "#D97706" }]}>
              PANGANDARAN
            </Text>
            <Text style={styles.destTitle}>Pantai &amp; Green Canyon</Text>
            <Text style={styles.destSub}>Paket 2H1M • Rombongan Kompak</Text>
          </View>
        </ScrollView>
      </View>

      {/* Fasilitas Termasuk Sewa */}
      <View style={styles.sectionBox}>
        <SectionHeader
          title="Fasilitas Termasuk Sewa"
          subtitle="Jaminan Layanan Standar Pariwisata PO Tunggal Jaya"
          style={{ marginBottom: 12 }}
        />
        <View style={styles.inclusiveGrid}>
          <View style={styles.inclusiveItem}>
            <Check size={16} color="#059669" />
            <Text style={styles.inclusiveText}>
              BBM Bus &amp; Armada Standar Pariwisata
            </Text>
          </View>
          <View style={styles.inclusiveItem}>
            <Check size={16} color="#059669" />
            <Text style={styles.inclusiveText}>
              Kru Driver &amp; Co-Driver Berlisensi Resmi
            </Text>
          </View>
          <View style={styles.inclusiveItem}>
            <Check size={16} color="#059669" />
            <Text style={styles.inclusiveText}>
              Audio Karaoke, TV LED &amp; Mic Wireless
            </Text>
          </View>
          <View style={styles.inclusiveItem}>
            <Check size={16} color="#059669" />
            <Text style={styles.inclusiveText}>
              Asuransi Jasa Raharja Resmi
            </Text>
          </View>
        </View>
      </View>
    </>
  );
};

const styles = StyleSheet.create({
  amenitiesCard: {
    backgroundColor: "#EFF6FF",
    borderRadius: 18,
    padding: 16,
    borderWidth: 1,
    borderColor: "#BFDBFE",
    marginBottom: 20,
  },
  amenitiesTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#1E40AF",
    marginBottom: 12,
  },
  amenitiesGrid: {
    flexDirection: "row",
    flexWrap: "wrap",
    gap: 12,
  },
  amenityItem: {
    flexDirection: "row",
    alignItems: "center",
    width: "47%",
    gap: 8,
  },
  amenityText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#1E3A8A",
  },
  sectionBox: {
    marginBottom: 20,
  },
  destScroll: {
    gap: 10,
  },
  destCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 14,
    padding: 14,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    width: 200,
  },
  destCity: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 11,
    color: "#2563EB",
    letterSpacing: 0.5,
    marginBottom: 4,
  },
  destTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12.5,
    color: "#111827",
  },
  destSub: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10.5,
    color: "#6B7280",
    marginTop: 4,
  },
  inclusiveGrid: {
    backgroundColor: "#FFFFFF",
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: "#E5E7EB",
    gap: 10,
  },
  inclusiveItem: {
    flexDirection: "row",
    alignItems: "center",
    gap: 10,
  },
  inclusiveText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#374151",
  },
});
