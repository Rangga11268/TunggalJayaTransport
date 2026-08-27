import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Alert,
  Platform,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import {
  ArrowLeft,
  Tag,
  Copy,
  CheckCircle,
  Percent,
  Sparkles,
  Crown,
  Bus,
  Compass,
  Clock,
} from 'lucide-react-native';

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

interface PromoItem {
  id: string;
  category: 'all' | 'akap' | 'charter' | 'vip';
  code: string;
  badge: string;
  discountText: string;
  discountSub: string;
  title: string;
  minSpend: string;
  validUntil: string;
  terms: string;
}

const PROMOS: PromoItem[] = [
  {
    id: '1',
    category: 'akap',
    code: 'TJBERKAH',
    badge: 'TIKET REGULER',
    discountText: '10% OFF',
    discountSub: 'Maks. Rp 25.000',
    title: 'Diskon Spesial Tiket AKAP',
    minSpend: 'Min. Belanja Rp 100.000',
    validUntil: '31 Des 2026',
    terms: 'Berlaku untuk semua rute reguler (Jakarta ↔ Kuningan / Cirebon)',
  },
  {
    id: '2',
    category: 'charter',
    code: 'TJPARIWISATA',
    badge: 'SEWA ROMBONGAN',
    discountText: 'Rp 250rb',
    discountSub: 'Cashback Langsung',
    title: 'Cashback Sewa Bus Pariwisata',
    minSpend: 'Min. Sewa 2 Hari',
    validUntil: '31 Des 2026',
    terms: 'Berlaku untuk pemesanan Big Bus & Double Decker',
  },
  {
    id: '3',
    category: 'vip',
    code: 'VIPMEMBER',
    badge: 'VIP LOYALTY',
    discountText: '15% OFF',
    discountSub: 'Khusus Member',
    title: 'Potongan Eksklusif Member Setia',
    minSpend: 'Tanpa Minimum',
    validUntil: 'Selalu Aktif',
    terms: 'Otomatis aktif untuk semua pengguna dengan status VIP',
  },
  {
    id: '4',
    category: 'akap',
    code: 'TJHEMAT20',
    badge: 'EARLY BIRD',
    discountText: 'Rp 20rb',
    discountSub: 'Potongan Flat',
    title: 'Kupon Hemat Keberangkatan Pagi',
    minSpend: 'Min. 1 Tiket',
    validUntil: '31 Des 2026',
    terms: 'Khusus jadwal keberangkatan bus pukul 06.00 - 08.00 WIB',
  },
];

export default function PromoScreen() {
  const navigation = useNavigation<NavigationProp>();
  const [activeCategory, setActiveCategory] = useState<'all' | 'akap' | 'charter' | 'vip'>('all');
  const [copiedCode, setCopiedCode] = useState<string | null>(null);

  const filterTabs = [
    { id: 'all', label: 'Semua (4)' },
    { id: 'akap', label: 'Tiket AKAP' },
    { id: 'charter', label: 'Pariwisata' },
    { id: 'vip', label: 'VIP Member' },
  ];

  const filteredPromos = activeCategory === 'all'
    ? PROMOS
    : PROMOS.filter((p) => p.category === activeCategory);

  const handleCopyCode = (code: string) => {
    setCopiedCode(code);
    Alert.alert(
      'Kupon Berhasil Disalin!',
      `Kode ${code} siap ditempel pada halaman checkout pembayaran tiket.`,
      [
        {
          text: 'Pesan Tiket Sekarang',
          onPress: () => navigation.navigate('Schedules'),
        },
        { text: 'Tutup', style: 'cancel' },
      ]
    );
    setTimeout(() => setCopiedCode(null), 3000);
  };

  return (
    <View style={styles.container}>
      {/* Top Header */}
      <SafeAreaView edges={['top']} style={styles.topBar}>
        <TouchableOpacity
          activeOpacity={0.7}
          onPress={() => navigation.goBack()}
          style={styles.backBtn}
        >
          <ArrowLeft size={18} color="#111827" />
        </TouchableOpacity>

        <Text style={styles.topBarTitle}>Voucher &amp; Kupon Promo</Text>
        <View style={{ width: 40 }} />
      </SafeAreaView>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* Banner Promo Hero */}
        <LinearGradient
          colors={['#E60023', '#C4001E']}
          start={{ x: 0, y: 0 }}
          end={{ x: 1, y: 1 }}
          style={styles.heroBanner}
        >
          <View style={styles.heroBannerContent}>
            <View style={styles.heroBadge}>
              <Sparkles size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
              <Text style={styles.heroBadgeText}>HEMAT PERJALANAN</Text>
            </View>
            <Text style={styles.heroHeading}>Nikmati Diskon s.d 50%</Text>
            <Text style={styles.heroSubtitle}>
              Salin kode kupon di bawah dan gunakan saat memesan tiket bus atau sewa pariwisata.
            </Text>
          </View>
        </LinearGradient>

        {/* Filter Category Chips */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.filterChipsRow}
        >
          {filterTabs.map((tab) => {
            const isActive = activeCategory === tab.id;
            return (
              <TouchableOpacity
                key={tab.id}
                activeOpacity={0.8}
                onPress={() => setActiveCategory(tab.id as any)}
                style={[styles.filterChip, isActive ? styles.filterChipActive : styles.filterChipInactive]}
              >
                <Text style={[styles.filterChipText, isActive ? styles.filterChipTextActive : styles.filterChipTextInactive]}>
                  {tab.label}
                </Text>
              </TouchableOpacity>
            );
          })}
        </ScrollView>

        {/* Perforated Luxury Voucher Cards List */}
        <View style={styles.vouchersList}>
          {filteredPromos.map((item) => {
            const isCopied = copiedCode === item.code;
            return (
              <View key={item.id} style={styles.voucherCard}>
                {/* Left Ticket Stub (Discount Badge) */}
                <LinearGradient
                  colors={item.category === 'vip' ? ['#D97706', '#B45309'] : ['#E60023', '#C4001E']}
                  style={styles.voucherStub}
                >
                  <Tag size={20} color="#FFFFFF" style={{ marginBottom: 6 }} />
                  <Text style={styles.stubDiscount}>{item.discountText}</Text>
                  <Text style={styles.stubSub}>{item.discountSub}</Text>
                </LinearGradient>

                {/* Perforation Cutout Notches */}
                <View style={styles.notchTop} />
                <View style={styles.notchBottom} />
                <View style={styles.perforationLine} />

                {/* Right Ticket Body (Details & Copy Button) */}
                <View style={styles.voucherBody}>
                  <View style={styles.voucherHeaderRow}>
                    <View style={styles.voucherBadgePill}>
                      <Text style={styles.voucherBadgePillText}>{item.badge}</Text>
                    </View>
                    <Text style={styles.voucherExpiry}>
                      <Clock size={11} color="#6B7280" /> s.d {item.validUntil}
                    </Text>
                  </View>

                  <Text style={styles.voucherTitle}>{item.title}</Text>
                  <Text style={styles.voucherMinSpend}>{item.minSpend}</Text>
                  <Text style={styles.voucherTerms}>{item.terms}</Text>

                  {/* Code & Copy Bar */}
                  <View style={styles.codeBar}>
                    <View style={styles.codeBox}>
                      <Text style={styles.codeText}>{item.code}</Text>
                    </View>

                    <TouchableOpacity
                      activeOpacity={0.85}
                      onPress={() => handleCopyCode(item.code)}
                      style={[styles.copyBtn, isCopied && styles.copyBtnSuccess]}
                    >
                      {isCopied ? (
                        <>
                          <CheckCircle size={14} color="#FFFFFF" style={{ marginRight: 4 }} />
                          <Text style={styles.copyBtnText}>Tersalin</Text>
                        </>
                      ) : (
                        <>
                          <Copy size={14} color="#FFFFFF" style={{ marginRight: 4 }} />
                          <Text style={styles.copyBtnText}>Salin</Text>
                        </>
                      )}
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            );
          })}
        </View>

        <View style={{ height: 40 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.bgDark,
  },
  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingVertical: 12,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#E2E8F0',
  },
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#F4F6F9',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  topBarTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 16,
    color: '#111827',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
  },
  heroBanner: {
    borderRadius: 22,
    padding: 20,
    marginBottom: 20,
    ...Platform.select({
      ios: {
        shadowColor: COLORS.brandRed,
        shadowOffset: { width: 0, height: 6 },
        shadowOpacity: 0.25,
        shadowRadius: 12,
      },
      android: {
        elevation: 4,
      },
    }),
  },
  heroBannerContent: {},
  heroBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    backgroundColor: 'rgba(255, 255, 255, 0.25)',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 10,
    marginBottom: 8,
  },
  heroBadgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 10,
    color: '#FFFFFF',
    letterSpacing: 0.5,
  },
  heroHeading: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 20,
    color: '#FFFFFF',
    marginBottom: 4,
  },
  heroSubtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: 'rgba(255, 255, 255, 0.88)',
    lineHeight: 18,
  },
  filterChipsRow: {
    flexDirection: 'row',
    gap: 10,
    marginBottom: 20,
  },
  filterChip: {
    paddingHorizontal: 16,
    paddingVertical: 9,
    borderRadius: 20,
  },
  filterChipActive: {
    backgroundColor: COLORS.brandRed,
  },
  filterChipInactive: {
    backgroundColor: '#FFFFFF',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  filterChipText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 12,
  },
  filterChipTextActive: {
    color: '#FFFFFF',
  },
  filterChipTextInactive: {
    color: '#4B5563',
  },
  vouchersList: {
    gap: 16,
  },
  voucherCard: {
    flexDirection: 'row',
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    overflow: 'hidden',
    position: 'relative',
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
      },
      android: {
        elevation: 2,
      },
    }),
  },
  voucherStub: {
    width: 95,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 8,
    paddingVertical: 18,
  },
  stubDiscount: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 16,
    color: '#FFFFFF',
    textAlign: 'center',
  },
  stubSub: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 10,
    color: 'rgba(255, 255, 255, 0.85)',
    textAlign: 'center',
    marginTop: 2,
  },
  notchTop: {
    position: 'absolute',
    left: 87,
    top: -8,
    width: 16,
    height: 16,
    borderRadius: 8,
    backgroundColor: COLORS.bgDark,
    zIndex: 10,
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  notchBottom: {
    position: 'absolute',
    left: 87,
    bottom: -8,
    width: 16,
    height: 16,
    borderRadius: 8,
    backgroundColor: COLORS.bgDark,
    zIndex: 10,
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  perforationLine: {
    width: 1,
    borderStyle: 'dashed',
    borderWidth: 1,
    borderColor: '#CBD5E1',
    marginVertical: 10,
  },
  voucherBody: {
    flex: 1,
    padding: 14,
  },
  voucherHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 6,
  },
  voucherBadgePill: {
    backgroundColor: '#F1F4F8',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 6,
  },
  voucherBadgePillText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 9,
    color: '#4B5563',
  },
  voucherExpiry: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 10,
    color: '#6B7280',
  },
  voucherTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#111827',
    marginBottom: 2,
  },
  voucherMinSpend: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 11,
    color: COLORS.brandRed,
    marginBottom: 4,
  },
  voucherTerms: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
    lineHeight: 16,
    marginBottom: 10,
  },
  codeBar: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  codeBox: {
    flex: 1,
    backgroundColor: '#F1F4F8',
    borderWidth: 1,
    borderColor: '#E2E8F0',
    borderStyle: 'dashed',
    paddingVertical: 6,
    paddingHorizontal: 10,
    borderRadius: 8,
  },
  codeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 12,
    color: '#111827',
    letterSpacing: 1,
  },
  copyBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.brandRed,
    paddingVertical: 7,
    paddingHorizontal: 14,
    borderRadius: 8,
  },
  copyBtnSuccess: {
    backgroundColor: '#059669',
  },
  copyBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 12,
    color: '#FFFFFF',
  },
});
