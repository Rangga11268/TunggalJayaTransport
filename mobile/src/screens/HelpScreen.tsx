import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Linking,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import {
  HelpCircle,
  MessageSquare,
  Phone,
  Mail,
  ChevronDown,
  ChevronUp,
} from 'lucide-react-native';

export default function HelpScreen() {
  const insets = useSafeAreaInsets();
  const [expandedFaq, setExpandedFaq] = useState<number | null>(null);

  const faqs = [
    {
      q: 'Bagaimana cara memesan tiket bus AKAP?',
      a: 'Pilih rute & tanggal keberangkatan di menu Home atau Jadwal, pilih kursi favorit Anda, isi data penumpang, dan lakukan pembayaran instan melalui Midtrans (QRIS/Transfer/E-Wallet).',
    },
    {
      q: 'Bagaimana cara menggunakan E-Tiket saat keberangkatan?',
      a: 'Buka menu Pesanan / Riwayat, pilih tiket Anda, dan tunjukkan QR Code pada E-Tiket kepada petugas atau kondektur saat naik bus.',
    },
    {
      q: 'Apakah saya bisa mengajukan sewa bus pariwisata?',
      a: 'Tentu saja! Masuk ke menu Sewa Pariwisata, masukkan detail penjemputan, tujuan, dan tanggal sewa. Tim operasional kami akan segera merespons dengan penawaran terbaik.',
    },
    {
      q: 'Bagaimana jika saya ingin mengubah jadwal keberangkatan?',
      a: 'Perubahan jadwal dapat dilakukan maksimal 6 jam sebelum jam keberangkatan bus dengan menghubungi layanan WhatsApp Customer Care resmi kami.',
    },
  ];

  const openWhatsApp = () => {
    Linking.openURL('https://wa.me/628123456789?text=Halo%20CS%20Tunggal%20Jaya%20Transport,%20saya%20butuh%20bantuan%20terkait%20pemesanan%20tiket.');
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <Text style={styles.headerTitle}>Pusat Bantuan & CS</Text>
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 120 }}
        showsVerticalScrollIndicator={false}
      >
        {/* WhatsApp Card */}
        <View style={styles.csCard}>
          <MessageSquare size={26} color={Colors.primary} style={{ marginBottom: 10 }} />
          <Text style={styles.csTitle}>Customer Service 24/7</Text>
          <Text style={styles.csDesc}>
            Butuh bantuan cepat atau konfirmasi pesanan? Hubungi tim support kami via WhatsApp resmi.
          </Text>

          <TouchableOpacity
            style={styles.waBtn}
            onPress={openWhatsApp}
            activeOpacity={0.85}
          >
            <Text style={styles.waBtnText}>Chat WhatsApp Resmi</Text>
          </TouchableOpacity>
        </View>

        {/* FAQs */}
        <Text style={styles.faqSectionTitle}>Pertanyaan yang Sering Diajukan</Text>

        {faqs.map((faq, idx) => {
          const isExpanded = expandedFaq === idx;
          return (
            <TouchableOpacity
              key={idx}
              style={styles.faqCard}
              onPress={() => setExpandedFaq(isExpanded ? null : idx)}
              activeOpacity={0.8}
            >
              <View style={styles.faqHeader}>
                <HelpCircle size={16} color={Colors.primary} style={{ marginRight: 8 }} />
                <Text style={styles.faqQuestion}>{faq.q}</Text>
                {isExpanded ? (
                  <ChevronUp size={16} color={Colors.textSecondary} />
                ) : (
                  <ChevronDown size={16} color={Colors.textSecondary} />
                )}
              </View>
              {isExpanded && <Text style={styles.faqAnswer}>{faq.a}</Text>}
            </TouchableOpacity>
          );
        })}
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  topHeader: {
    paddingHorizontal: 20,
    paddingBottom: 14,
    backgroundColor: Colors.surfaceCard,
    borderBottomWidth: 1.2,
    borderBottomColor: Colors.border,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  csCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 24,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 22,
    marginTop: 16,
  },
  csTitle: {
    fontSize: 18,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  csDesc: {
    fontSize: 12,
    color: Colors.textSecondary,
    marginTop: 4,
    lineHeight: 18,
    marginBottom: 16,
  },
  waBtn: {
    height: 48,
    backgroundColor: Colors.primary,
    borderRadius: Radius.pill,
    justifyContent: 'center',
    alignItems: 'center',
  },
  waBtnText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '800',
  },
  faqSectionTitle: {
    fontSize: 16,
    fontWeight: '800',
    color: '#FFFFFF',
    marginTop: 24,
    marginBottom: 12,
  },
  faqCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 18,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 16,
    marginBottom: 10,
  },
  faqHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  faqQuestion: {
    flex: 1,
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '700',
  },
  faqAnswer: {
    color: Colors.textSecondary,
    fontSize: 12,
    lineHeight: 18,
    marginTop: 10,
    paddingTop: 8,
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
});
