import React, { useState, useMemo } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Linking,
  Platform,
  TextInput,
} from "react-native";
import { COLORS } from "../theme/colors";
import { ScreenHeader } from "../components/ScreenHeader";
import {
  PhoneCall,
  ChevronDown,
  ChevronUp,
  ShieldCheck,
  ExternalLink,
  Search,
  MapPin,
  HelpCircle,
} from "lucide-react-native";
import {
  OfficialWhatsAppIcon,
  OfficialInstagramIcon,
  FaqAkapBookingIcon,
  FaqQrBoardingIcon,
  FaqCharterIcon,
  FaqRescheduleClockIcon,
  FaqPaymentCardIcon,
  FaqLuggageBagIcon,
} from "../components/ServiceIcons";

export default function HelpScreen() {
  const [expandedFaq, setExpandedFaq] = useState<number | null>(0);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState<
    "all" | "akap" | "charter" | "payment" | "baggage"
  >("all");

  const contactChannels = [
    {
      id: "wa-cs",
      title: "WhatsApp Customer Care 24 Jam",
      subtitle: "Siaga 24 Jam • Respon cepat di bawah 5 menit",
      iconColor: "#16A34A",
      action: () =>
        Linking.openURL(
          "https://wa.me/6281122222353?text=Halo%20CS%20PO%20Tunggal%20Jaya,%20saya%20butuh%20bantuan%20pemesanan%20tiket.",
        ),
      btnLabel: "Chat WhatsApp",
      btnBg: "#16A34A",
      iconBg: "#DCFCE7",
      isPrimary: true,
    },
    {
      id: "call-center",
      title: "Call Center Garasi Cilimus",
      subtitle: "Hotline Kantor Pusat Kuningan (0232) 613399",
      iconColor: "#2563EB",
      action: () => Linking.openURL("tel:0232613399"),
      btnLabel: "Telepon (0232) 613399",
      btnBg: "#2563EB",
      iconBg: "#EFF6FF",
      isPrimary: false,
    },
    {
      id: "instagram",
      title: "Instagram Resmi PO Tunggal Jaya",
      subtitle: "@tunggal_jaya_transport • Update armada & info",
      iconColor: "#E1306C",
      action: () =>
        Linking.openURL("https://instagram.com/tunggal_jaya_transport"),
      btnLabel: "Kunjungi Instagram",
      btnBg: "#E1306C",
      iconBg: "#FCE7F3",
      isPrimary: false,
    },
  ];

  const faqs = [
    {
      category: "akap",
      iconSvg: FaqAkapBookingIcon,
      q: "Bagaimana alur pemesanan tiket bus AKAP?",
      a: "1. Pilih kota asal dan tujuan di menu Beranda atau Jadwal.\n2. Pilih nomor kursi favorit pada denah kabin bus interaktif.\n3. Isi data identitas penumpang (nama & nomor WhatsApp aktif).\n4. Lakukan pembayaran instan via QRIS realtime atau transfer bank.\n5. E-Tiket Boarding Pass langsung terbit dan tersimpan di tab Pesanan.",
    },
    {
      category: "akap",
      iconSvg: FaqQrBoardingIcon,
      q: "Bagaimana cara boarding menggunakan E-Tiket saat hari H?",
      a: "Cukup buka menu Pesanan di aplikasi mobile, pilih tiket perjalanan Anda, lalu tunjukkan QR Code Boarding Pass kepada kondektur atau kru agen sebelum menaiki bus.",
    },
    {
      category: "charter",
      iconSvg: FaqCharterIcon,
      q: "Bagaimana prosedur sewa bus pariwisata rombongan?",
      a: "Buka menu Sewa Pariwisata, pilih unit armada idaman (Kylo Ren, Takumi, Jupiter, atau Winata), tentukan titik penjemputan, tanggal sewa, dan kota tujuan. Tim reservasi pariwisata resmi kami akan mengonfirmasi via WhatsApp dalam hitungan menit.",
    },
    {
      category: "akap",
      iconSvg: FaqRescheduleClockIcon,
      q: "Apakah jadwal keberangkatan tiket bisa di-reschedule?",
      a: "Perubahan jadwal (reschedule) tiket dapat diproses maksimal 6 jam sebelum jam keberangkatan bus dengan menghubungi WhatsApp Customer Service resmi kami.",
    },
    {
      category: "payment",
      iconSvg: FaqPaymentCardIcon,
      q: "Metode pembayaran apa saja yang didukung?",
      a: "Kami mendukung pembayaran QRIS otomatis (GoPay, OVO, Dana, ShopeePay, BCA Mobile, Livin Mandiri) serta Virtual Account Bank resmi (BCA, Mandiri, BNI, BRI) dengan verifikasi realtime tanpa perlu unggah bukti transfer manual.",
    },
    {
      category: "baggage",
      iconSvg: FaqLuggageBagIcon,
      q: "Berapa kapasitas bagasi gratis per penumpang?",
      a: "Setiap penumpang berhak membawa bagasi standar hingga 20 kg secara gratis. Untuk pengiriman paket kargo besar atau sepeda motor, harap hubungi agen keberangkatan terlebih dahulu.",
    },
  ];

  const filteredFaqs = useMemo(() => {
    return faqs.filter((faq) => {
      // Category filter
      if (selectedCategory !== "all" && faq.category !== selectedCategory) {
        return false;
      }
      // Search query filter
      if (searchQuery.trim()) {
        const q = searchQuery.toLowerCase();
        return (
          faq.q.toLowerCase().includes(q) || faq.a.toLowerCase().includes(q)
        );
      }
      return true;
    });
  }, [faqs, selectedCategory, searchQuery]);

  return (
    <View style={styles.container}>
      {/* Standard Screen Header */}
      <ScreenHeader
        title="Pusat Bantuan & CS"
        subtitle="Solusi Cepat, Kontak CS & Info Garasi"
      />

      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* 1. FAQ SECTION (PALING ATAS) */}
        <View style={styles.faqHeaderSection}>
          <Text style={styles.faqSectionTitle}>
            Pertanyaan yang Sering Diajukan
          </Text>
          <Text style={styles.faqSectionSubtitle}>
            Panduan Lengkap &amp; Solusi Kendala Pemesanan
          </Text>
        </View>

        {/* FAQ Search Bar */}
        <View style={styles.faqSearchBar}>
          <Search size={16} color="#94A3B8" style={{ marginRight: 8 }} />
          <TextInput
            placeholder="Cari pertanyaan (reschedule, tiket, bagasi, bayar)..."
            placeholderTextColor="#94A3B8"
            value={searchQuery}
            onChangeText={setSearchQuery}
            style={styles.faqSearchInput}
          />
        </View>

        {/* FAQ Category Filter Chips */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.faqChipsRow}
        >
          <TouchableOpacity
            onPress={() => setSelectedCategory("all")}
            style={[
              styles.faqChip,
              selectedCategory === "all" && styles.faqChipActive,
            ]}
          >
            <Text
              style={[
                styles.faqChipText,
                selectedCategory === "all" && styles.faqChipTextActive,
              ]}
            >
              Semua FAQ
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => setSelectedCategory("akap")}
            style={[
              styles.faqChip,
              selectedCategory === "akap" && styles.faqChipActive,
            ]}
          >
            <Text
              style={[
                styles.faqChipText,
                selectedCategory === "akap" && styles.faqChipTextActive,
              ]}
            >
              Tiket AKAP
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => setSelectedCategory("charter")}
            style={[
              styles.faqChip,
              selectedCategory === "charter" && styles.faqChipActive,
            ]}
          >
            <Text
              style={[
                styles.faqChipText,
                selectedCategory === "charter" && styles.faqChipTextActive,
              ]}
            >
              Pariwisata
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => setSelectedCategory("payment")}
            style={[
              styles.faqChip,
              selectedCategory === "payment" && styles.faqChipActive,
            ]}
          >
            <Text
              style={[
                styles.faqChipText,
                selectedCategory === "payment" && styles.faqChipTextActive,
              ]}
            >
              Pembayaran
            </Text>
          </TouchableOpacity>

          <TouchableOpacity
            onPress={() => setSelectedCategory("baggage")}
            style={[
              styles.faqChip,
              selectedCategory === "baggage" && styles.faqChipActive,
            ]}
          >
            <Text
              style={[
                styles.faqChipText,
                selectedCategory === "baggage" && styles.faqChipTextActive,
              ]}
            >
              Bagasi
            </Text>
          </TouchableOpacity>
        </ScrollView>

        {/* FAQ Accordion List (Spacious & Rich Vector SVG Badges) */}
        <View style={styles.faqListContainer}>
          {filteredFaqs.length === 0 ? (
            <View style={styles.faqEmptyBox}>
              <HelpCircle size={32} color="#CBD5E1" />
              <Text style={styles.faqEmptyText}>
                Tidak ada FAQ yang cocok dengan pencarian Anda.
              </Text>
            </View>
          ) : (
            filteredFaqs.map((faq, idx) => {
              const isExpanded = expandedFaq === idx;
              const SvgIcon = faq.iconSvg;

              return (
                <View
                  key={idx}
                  style={[
                    styles.faqCard,
                    isExpanded && styles.faqCardExpanded,
                  ]}
                >
                  <TouchableOpacity
                    style={styles.faqHeaderBtn}
                    onPress={() => setExpandedFaq(isExpanded ? null : idx)}
                    activeOpacity={0.8}
                  >
                    <View style={styles.faqIconSquare}>
                      <SvgIcon size={38} />
                    </View>

                    <Text style={styles.faqQuestionText}>{faq.q}</Text>

                    <View style={styles.faqChevronBox}>
                      {isExpanded ? (
                        <ChevronUp size={16} color="#0F172A" />
                      ) : (
                        <ChevronDown size={16} color="#64748B" />
                      )}
                    </View>
                  </TouchableOpacity>

                  {isExpanded && (
                    <View style={styles.faqBodyContainer}>
                      <Text style={styles.faqAnswerText}>{faq.a}</Text>
                    </View>
                  )}
                </View>
              );
            })
          )}
        </View>

        {/* 2. CS CHANNELS & SOCIAL MEDIA (SETELAH FAQ) */}
        <View style={styles.sectionTitleBlock}>
          <Text style={styles.sectionBlockTitle}>
            Kontak CS &amp; Media Sosial
          </Text>
          <Text style={styles.sectionBlockSubtitle}>
            Customer Care Siaga 24 Jam PO Tunggal Jaya
          </Text>
        </View>

        <View style={styles.channelsList}>
          {contactChannels.map((ch) => {
            return (
              <View
                key={ch.id}
                style={[
                  styles.channelCard,
                  ch.isPrimary && styles.channelCardPrimary,
                ]}
              >
                <View style={styles.channelTopRow}>
                  <View
                    style={[
                      styles.channelIconBox,
                      { backgroundColor: ch.iconBg },
                    ]}
                  >
                    {ch.id === "wa-cs" ? (
                      <OfficialWhatsAppIcon size={24} color="#16A34A" />
                    ) : ch.id === "instagram" ? (
                      <OfficialInstagramIcon size={24} color="#E1306C" />
                    ) : (
                      <PhoneCall size={22} color="#2563EB" />
                    )}
                  </View>

                  <View style={styles.channelTextContainer}>
                    <Text style={styles.channelTitleText}>{ch.title}</Text>
                    <Text style={styles.channelSubtitleText}>
                      {ch.subtitle}
                    </Text>
                  </View>
                </View>

                <TouchableOpacity
                  activeOpacity={0.85}
                  onPress={ch.action}
                  style={[
                    styles.channelActionBtn,
                    { backgroundColor: ch.btnBg },
                  ]}
                >
                  {ch.id === "wa-cs" && (
                    <OfficialWhatsAppIcon
                      size={17}
                      color="#FFFFFF"
                      style={{ marginRight: 6 }}
                    />
                  )}
                  {ch.id === "call-center" && (
                    <PhoneCall
                      size={15}
                      color="#FFFFFF"
                      style={{ marginRight: 6 }}
                    />
                  )}
                  {ch.id === "instagram" && (
                    <OfficialInstagramIcon
                      size={16}
                      color="#FFFFFF"
                      style={{ marginRight: 6 }}
                    />
                  )}
                  <Text style={styles.channelActionBtnText}>
                    {ch.btnLabel}
                  </Text>
                  <ExternalLink
                    size={14}
                    color="#FFFFFF"
                    style={{ marginLeft: 6 }}
                  />
                </TouchableOpacity>
              </View>
            );
          })}
        </View>

        {/* 3. LOKASI GARASI & POOL RESMI (SETELAH KONTAK CS) */}
        <View style={styles.garageCardWrapper}>
          <View style={styles.garageCardHeader}>
            <MapPin size={16} color={COLORS.brandBlue} style={{ marginRight: 6 }} />
            <Text style={styles.garageCardMainTitle}>
              Lokasi Garasi &amp; Pool Resmi
            </Text>
          </View>

          {/* Garasi 1 */}
          <View style={styles.garageItemBox}>
            <View style={styles.garagePillRow}>
              <View style={styles.garagePill1}>
                <Text style={styles.garagePill1Text}>
                  GARASI 1 (PUSAT &amp; PARIWISATA)
                </Text>
              </View>
            </View>
            <Text style={styles.garageTitle}>
              Garasi Pusat Cilimus, Kuningan
            </Text>
            <Text style={styles.garageAddress}>
              Jl. Raya Linggajati, Bojong, Kec. Cilimus, Kabupaten Kuningan,
              Jawa Barat 45556 • Hotline: (0232) 613399
            </Text>
          </View>

          <View style={styles.garageDivider} />

          {/* Garasi 2 */}
          <View style={styles.garageItemBox}>
            <View style={styles.garagePillRow}>
              <View style={styles.garagePill2}>
                <Text style={styles.garagePill2Text}>
                  GARASI 2 (KHUSUS BUS AKAP)
                </Text>
              </View>
            </View>
            <Text style={styles.garageTitle}>Garasi Cidahu, Kuningan</Text>
            <Text style={styles.garageAddress}>
              Cihideunggirang, Kec. Cidahu, Kabupaten Kuningan, Jawa Barat
              45595 • Pool AKAP Tol Cipali &amp; Bengkel Terpadu
            </Text>
          </View>

          {/* Direct Google Maps Direction Link */}
          <TouchableOpacity
            activeOpacity={0.8}
            onPress={() =>
              Linking.openURL("https://maps.google.com/?q=-6.881759,108.491583")
            }
            style={styles.mapsDirectionBtn}
          >
            <MapPin size={15} color={COLORS.brandBlue} style={{ marginRight: 6 }} />
            <Text style={styles.mapsDirectionBtnText}>
              Buka Petunjuk Arah di Google Maps
            </Text>
            <ExternalLink
              size={13}
              color={COLORS.brandBlue}
              style={{ marginLeft: 4 }}
            />
          </TouchableOpacity>
        </View>

        {/* Security & Official Warranty Guarantee */}
        <View style={styles.securityGuaranteeCard}>
          <ShieldCheck size={20} color="#059669" style={{ marginRight: 10 }} />
          <Text style={styles.securityGuaranteeText}>
            Pemesanan tiket online terhubung langsung ke sistem database resmi PO
            Tunggal Jaya Transport.
          </Text>
        </View>

        <View style={{ height: 110 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
  },
  faqHeaderSection: {
    marginBottom: 10,
  },
  faqSectionTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
  },
  faqSectionSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 2,
  },
  faqSearchBar: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#FFFFFF",
    borderRadius: 12,
    paddingHorizontal: 12,
    height: 42,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 12,
  },
  faqSearchInput: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#111827",
    paddingVertical: 0,
  },
  faqChipsRow: {
    flexDirection: "row",
    gap: 8,
    marginBottom: 14,
  },
  faqChip: {
    paddingHorizontal: 12,
    paddingVertical: 7,
    borderRadius: 10,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
  },
  faqChipActive: {
    backgroundColor: COLORS.brandBlue,
    borderColor: COLORS.brandBlue,
  },
  faqChipText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11.5,
    color: "#64748B",
  },
  faqChipTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  faqListContainer: {
    gap: 10,
    marginBottom: 24,
  },
  faqEmptyBox: {
    alignItems: "center",
    paddingVertical: 30,
  },
  faqEmptyText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#94A3B8",
    marginTop: 8,
  },
  faqCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 18,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    overflow: "hidden",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  faqCardExpanded: {
    borderColor: "#93C5FD",
  },
  faqHeaderBtn: {
    flexDirection: "row",
    alignItems: "center",
    padding: 12,
    gap: 10,
  },
  faqIconSquare: {
    width: 40,
    height: 40,
    justifyContent: "center",
    alignItems: "center",
  },
  faqQuestionText: {
    flex: 1,
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#0F172A",
    lineHeight: 18,
  },
  faqChevronBox: {
    width: 28,
    height: 28,
    borderRadius: 14,
    backgroundColor: "#F1F5F9",
    justifyContent: "center",
    alignItems: "center",
  },
  faqBodyContainer: {
    paddingHorizontal: 16,
    paddingBottom: 14,
    paddingTop: 4,
    borderTopWidth: 1,
    borderTopColor: "#F1F5F9",
    backgroundColor: "#F8FAFC",
  },
  faqAnswerText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#334155",
    lineHeight: 19,
  },
  sectionTitleBlock: {
    marginBottom: 12,
  },
  sectionBlockTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 16,
    color: "#0F172A",
  },
  sectionBlockSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 2,
  },
  channelsList: {
    gap: 12,
    marginBottom: 24,
  },
  channelCard: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  channelCardPrimary: {
    borderColor: "#BBF7D0",
    backgroundColor: "#FFFFFF",
  },
  channelTopRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
  },
  channelIconBox: {
    width: 46,
    height: 46,
    borderRadius: 14,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  channelTextContainer: {
    flex: 1,
  },
  channelTitleText: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#0F172A",
    marginBottom: 2,
  },
  channelSubtitleText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
  },
  channelActionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    height: 44,
    borderRadius: 12,
  },
  channelActionBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#FFFFFF",
  },
  garageCardWrapper: {
    backgroundColor: "#FFFFFF",
    borderRadius: 20,
    padding: 16,
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 22,
    ...Platform.select({
      ios: {
        shadowColor: "#0F172A",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.04,
        shadowRadius: 6,
      },
      android: {
        elevation: 1,
      },
    }),
  },
  garageCardHeader: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 14,
  },
  garageCardMainTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 14,
    color: "#0F172A",
  },
  garageItemBox: {
    marginBottom: 4,
  },
  garagePillRow: {
    marginBottom: 6,
  },
  garagePill1: {
    alignSelf: "flex-start",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  garagePill1Text: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#2563EB",
    letterSpacing: 0.3,
  },
  garagePill2: {
    alignSelf: "flex-start",
    backgroundColor: "#F1F5F9",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  garagePill2Text: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: "#475569",
    letterSpacing: 0.3,
  },
  garageTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 13.5,
    color: "#0F172A",
  },
  garageAddress: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 3,
    lineHeight: 17,
  },
  garageDivider: {
    height: 1,
    backgroundColor: "#F1F5F9",
    marginVertical: 12,
  },
  mapsDirectionBtn: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: "#EFF6FF",
    paddingVertical: 11,
    borderRadius: 12,
    marginTop: 12,
    borderWidth: 1,
    borderColor: "#BFDBFE",
  },
  mapsDirectionBtnText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 12,
    color: COLORS.brandBlue,
  },
  securityGuaranteeCard: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(5, 150, 105, 0.08)",
    borderRadius: 16,
    padding: 14,
    borderWidth: 1,
    borderColor: "rgba(5, 150, 105, 0.2)",
  },
  securityGuaranteeText: {
    flex: 1,
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11,
    color: "#059669",
    lineHeight: 16,
  },
});
