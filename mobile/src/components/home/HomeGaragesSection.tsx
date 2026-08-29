import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  StyleSheet,
  Linking,
  Platform,
} from "react-native";
import { MapPin, PhoneCall, ExternalLink } from "lucide-react-native";
import { SectionHeader } from "../SectionHeader";
import { OfficialWhatsAppIcon } from "../ServiceIcons";
import { COLORS } from "../../theme/colors";

export const HomeGaragesSection: React.FC = () => {
  return (
    <View style={styles.garageSectionContainer}>
      <SectionHeader
        title="Garasi &amp; Kontak Resmi"
        subtitle="Lokasi Operasional &amp; Layanan Bantuan 24 Jam"
        style={{ marginBottom: 14 }}
      />

      {/* Garasi 1: Pusat & Pariwisata */}
      <View style={styles.garagePhotoCard}>
        <View style={styles.garageImageWrapper}>
          <Image
            source={require("../../../assets/images/garasi1_cilimus.webp")}
            style={styles.garageImage}
            resizeMode="cover"
          />
          <View style={styles.garageImageBadge}>
            <Text style={styles.garageImageBadgeText}>
              GARASI 1 (PUSAT &amp; PARIWISATA)
            </Text>
          </View>
        </View>

        <View style={styles.garageCardBody}>
          <Text style={styles.garageItemTitle}>
            Garasi Pusat Cilimus, Kuningan
          </Text>
          <Text style={styles.garageItemAddress}>
            Jl. Raya Linggajati, Bojong, Kec. Cilimus, Kabupaten Kuningan,
            Jawa Barat 45556
          </Text>

          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() =>
              Linking.openURL(
                "https://maps.google.com/?q=-6.881759,108.491583",
              )
            }
            style={styles.garageMapsBtn}
          >
            <MapPin
              size={13}
              color={COLORS.brandBlue}
              style={{ marginRight: 5 }}
            />
            <Text style={styles.garageMapsBtnText}>
              Petunjuk Arah Google Maps
            </Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Garasi 2: Khusus Bus AKAP */}
      <View style={styles.garagePhotoCard}>
        <View style={styles.garageImageWrapper}>
          <Image
            source={require("../../../assets/images/garasi2_cidahu.webp")}
            style={styles.garageImage}
            resizeMode="cover"
          />
          <View
            style={[
              styles.garageImageBadge,
              { backgroundColor: "rgba(37, 99, 235, 0.9)" },
            ]}
          >
            <Text style={styles.garageImageBadgeText}>
              GARASI 2 (KHUSUS BUS AKAP)
            </Text>
          </View>
        </View>

        <View style={styles.garageCardBody}>
          <Text style={styles.garageItemTitle}>
            Garasi Cidahu, Kuningan
          </Text>
          <Text style={styles.garageItemAddress}>
            Cihideunggirang, Kec. Cidahu, Kabupaten Kuningan, Jawa Barat
            45595 • Pool AKAP &amp; Bengkel Terpadu
          </Text>

          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() =>
              Linking.openURL(
                "https://maps.google.com/?q=-6.96324,108.62145",
              )
            }
            style={styles.garageMapsBtn}
          >
            <MapPin
              size={13}
              color={COLORS.brandBlue}
              style={{ marginRight: 5 }}
            />
            <Text style={styles.garageMapsBtnText}>
              Petunjuk Arah Google Maps
            </Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Dedicated Action Buttons */}
      <View style={styles.garageActionsWrapper}>
        <TouchableOpacity
          activeOpacity={0.85}
          onPress={() =>
            Linking.openURL(
              "https://wa.me/6281122222353?text=Halo%20CS%20PO%20Tunggal%20Jaya,%20saya%20ingin%20informasi%20jadwal%20dan%20sewa%20bus.",
            )
          }
          style={styles.officialWaButton}
        >
          <OfficialWhatsAppIcon size={18} color="#FFFFFF" />
          <Text style={styles.officialWaButtonText}>
            Chat WhatsApp CS 24 Jam
          </Text>
          <ExternalLink
            size={13}
            color="#FFFFFF"
            style={{ marginLeft: 4 }}
          />
        </TouchableOpacity>

        <TouchableOpacity
          activeOpacity={0.8}
          onPress={() => Linking.openURL("tel:0232613399")}
          style={styles.officialCallButton}
        >
          <PhoneCall size={14} color="#1E293B" style={{ marginRight: 6 }} />
          <Text style={styles.officialCallButtonText}>
            Telepon Kantor: (0232) 613399
          </Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  garageSectionContainer: {
    marginBottom: 10,
  },
  garagePhotoCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    overflow: "hidden",
    borderWidth: 1,
    borderColor: "#E2E8F0",
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
        boxShadow: "0 4px 12px rgba(15, 23, 42, 0.05)",
      } as any,
    }),
  },
  garageImageWrapper: {
    width: "100%",
    height: 145,
    position: "relative",
  },
  garageImage: {
    width: "100%",
    height: "100%",
  },
  garageImageBadge: {
    position: "absolute",
    top: 10,
    left: 10,
    backgroundColor: "rgba(15, 23, 42, 0.85)",
    paddingHorizontal: 9,
    paddingVertical: 4,
    borderRadius: 8,
  },
  garageImageBadgeText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 9.5,
    color: "#FFFFFF",
    letterSpacing: 0.3,
  },
  garageCardBody: {
    padding: 14,
  },
  garageItemTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
    marginBottom: 4,
  },
  garageItemAddress: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    lineHeight: 16,
    marginBottom: 10,
  },
  garageMapsBtn: {
    flexDirection: "row",
    alignItems: "center",
    alignSelf: "flex-start",
    backgroundColor: "#EFF6FF",
    borderWidth: 1,
    borderColor: "#BFDBFE",
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 8,
  },
  garageMapsBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: COLORS.brandBlue,
  },
  garageActionsWrapper: {
    flexDirection: "column",
    gap: 8,
    marginTop: 4,
  },
  officialWaButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#16A34A",
    borderRadius: 14,
    paddingVertical: 12,
    paddingHorizontal: 16,
    gap: 8,
    ...Platform.select({
      ios: {
        shadowColor: "#16A34A",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.2,
        shadowRadius: 8,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  officialWaButtonText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  officialCallButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#F1F5F9",
    borderWidth: 1,
    borderColor: "#CBD5E1",
    borderRadius: 14,
    paddingVertical: 10,
    paddingHorizontal: 16,
  },
  officialCallButtonText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: "#1E293B",
  },
});
