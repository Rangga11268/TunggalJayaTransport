import React from "react";
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Platform,
} from "react-native";
import { SectionHeader } from "../SectionHeader";
import {
  AkapBusIcon,
  CharterPariwisataIcon,
  BookingHistoryIcon,
  PromoVoucherIcon,
} from "../ServiceIcons";

interface HomeServicesGridProps {
  onNavigateSchedules: () => void;
  onNavigateCharter: () => void;
  onNavigateHistory: () => void;
  onNavigatePromo: () => void;
}

export const HomeServicesGrid: React.FC<HomeServicesGridProps> = ({
  onNavigateSchedules,
  onNavigateCharter,
  onNavigateHistory,
  onNavigatePromo,
}) => {
  const quickLinks = [
    {
      id: "schedules",
      title: "Tiket AKAP",
      subtitle: "Jadwal & Kursi",
      SvgIcon: AkapBusIcon,
      action: onNavigateSchedules,
    },
    {
      id: "charter",
      title: "Pariwisata",
      subtitle: "Sewa Bus TJ",
      SvgIcon: CharterPariwisataIcon,
      action: onNavigateCharter,
    },
    {
      id: "history",
      title: "Riwayat",
      subtitle: "E-Tiket & Status",
      SvgIcon: BookingHistoryIcon,
      action: onNavigateHistory,
    },
    {
      id: "promo",
      title: "Promo",
      subtitle: "Voucher Diskon",
      SvgIcon: PromoVoucherIcon,
      action: onNavigatePromo,
    },
  ];

  return (
    <View style={styles.quickSection}>
      <SectionHeader
        title="Layanan Utama"
        subtitle="Akses Cepat Pemesanan &amp; Informasi"
        style={{ marginBottom: 12 }}
      />

      <View style={styles.quickGrid}>
        {quickLinks.map((item) => {
          const SvgComp = item.SvgIcon;
          return (
            <TouchableOpacity
              key={item.id}
              activeOpacity={0.85}
              onPress={item.action}
              style={styles.quickCard}
            >
              <View style={styles.quickSvgBox}>
                <SvgComp size={48} />
              </View>
              <Text style={styles.quickTitle} numberOfLines={1}>
                {item.title}
              </Text>
              <Text style={styles.quickSubtitle} numberOfLines={1}>
                {item.subtitle}
              </Text>
            </TouchableOpacity>
          );
        })}
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  quickSection: {
    marginBottom: 24,
  },
  quickGrid: {
    flexDirection: "row",
    gap: 10,
  },
  quickCard: {
    flex: 1,
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    paddingVertical: 14,
    paddingHorizontal: 8,
    alignItems: "center",
    borderWidth: 1,
    borderColor: "#E2E8F0",
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
  quickSvgBox: {
    marginBottom: 8,
  },
  quickTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 12,
    color: "#0F172A",
    textAlign: "center",
  },
  quickSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#64748B",
    marginTop: 2,
    textAlign: "center",
  },
});
