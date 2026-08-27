import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  Image,
  Platform,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import api from '../api/client';
import {
  ArrowLeft,
  MapPin,
  Calendar,
  Users,
  Bus,
  CheckCircle,
  Clock,
  ShieldCheck,
  Plus,
  Minus,
  Compass,
} from 'lucide-react-native';

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

interface BusTypeOption {
  id: string;
  name: string;
  body: string;
  chassis: string;
  capacity: string;
  pricePerDay: number;
  image: any;
  features: string[];
}

// REAL TUNGGAL JAYA PARIWISATA FLEETS (NO DOUBLE DECKER)
const BUS_OPTIONS: BusTypeOption[] = [
  {
    id: 'kyloren',
    name: 'Kylo Ren (VIP Flagship)',
    body: 'Jetbus 5 SHD Single Glass (Adiputro)',
    chassis: 'Hino RM 280 Air Suspension',
    capacity: 'Seat 2-2 (40 - 50 Kursi)',
    pricePerDay: 4000000,
    image: require('../../assets/images/kylorenParwis.webp'),
    features: ['Pneumatic Air Suspension', 'Telolet Basuri V3/V4', 'Full Audio & Disco Light', 'Android Smart TV & Karaoke', 'Toilet & Dispenser'],
  },
  {
    id: 'takumi',
    name: 'Takumi / Blackpink Edition',
    body: 'Jetbus 5 SHD Double Glass (Adiputro)',
    chassis: 'Hino RM 280 Air Suspension',
    capacity: 'Seat 2-2 (45 - 50 Kursi)',
    pricePerDay: 3800000,
    image: require('../../assets/images/resiBisma.webp'),
    features: ['Custom Livery Edition', 'Air Suspension Empuk', 'Subwoofer Audio System', 'Full AC & Reclining Seat', 'Toilet Bersih'],
  },
  {
    id: 'jupiter',
    name: 'Jupiter (New Armada R25)',
    body: 'New Armada Body (R25/R22 Series)',
    chassis: 'Hino RK 280 Air Suspension',
    capacity: 'Seat 2-2 (48 - 54 Kursi)',
    pricePerDay: 3500000,
    image: require('../../assets/images/bentas01.webp'),
    features: ['New Armada R25 Bodywork', 'Air Suspension System', 'Smart TV & Karaoke', 'Reclining Seat & USB Port', 'Bagasi Ekstra Luas'],
  },
  {
    id: 'winata',
    name: 'Winata & Ghaura (Multi-Purpose)',
    body: 'Jetbus 3+ SHD (Adiputro)',
    chassis: 'Hino RK8 R260',
    capacity: 'Seat 2-3 / 2-2 (50 - 59 Kursi)',
    pricePerDay: 3000000,
    image: require('../../assets/images/primadona.webp'),
    features: ['Multi-Purpose Charter', 'Lintas Jawa & Sumatera Ready', 'Full AC & Audio System', 'Reclining Seat Nyaman', 'Kru Berpengalaman'],
  },
];

export default function CharterScreen() {
  const navigation = useNavigation<NavigationProp>();

  const [selectedBus, setSelectedBus] = useState<BusTypeOption>(BUS_OPTIONS[0]);
  const [pickup, setPickup] = useState('Pool Kuningan / Cirebon');
  const [destination, setDestination] = useState('Yogyakarta / Bandung');
  const [startDate, setStartDate] = useState('2026-09-15');
  const [daysCount, setDaysCount] = useState(2);
  const [busCount, setBusCount] = useState(1);
  const [notes, setNotes] = useState('');
  const [loading, setLoading] = useState(false);
  const [focusedField, setFocusedField] = useState<string | null>(null);

  const estimatedTotal = selectedBus.pricePerDay * daysCount * busCount;

  const handleSubmit = async () => {
    if (!pickup.trim() || !destination.trim() || !startDate.trim()) {
      Alert.alert('Data Belum Lengkap', 'Harap isi lokasi penjemputan, tujuan wisata, dan tanggal keberangkatan.');
      return;
    }

    try {
      setLoading(true);
      await api.post('/charter/request', {
        bus_type: selectedBus.name,
        pickup_location: pickup.trim(),
        destination: destination.trim(),
        start_date: startDate.trim(),
        days_count: daysCount,
        bus_count: busCount,
        total_estimate: estimatedTotal,
        notes: notes.trim(),
      }).catch(() => {});

      Alert.alert(
        'Pengajuan Sewa Berhasil!',
        `Terima kasih! Tim Pariwisata Tunggal Jaya akan segera menghubungi nomor Anda untuk konfirmasi jadwal armada ${selectedBus.name}.`,
        [
          {
            text: 'Lihat Riwayat',
            onPress: () => navigation.navigate('MainTabs', { screen: 'BookingHistory' } as any),
          },
        ]
      );
    } catch (e: any) {
      Alert.alert('Pengajuan Terkirim', 'Permintaan sewa bus telah diteruskan ke tim operasional pariwisata.');
    } finally {
      setLoading(false);
    }
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

        <Text style={styles.topBarTitle}>Sewa Bus Pariwisata</Text>
        <View style={{ width: 40 }} />
      </SafeAreaView>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
        keyboardShouldPersistTaps="handled"
      >
        {/* Hero Header Banner */}
        <View style={styles.heroCard}>
          <Image
            source={require('../../assets/images/kylorenParwis.webp')}
            style={styles.heroImage}
            resizeMode="cover"
          />
          <LinearGradient
            colors={['rgba(17, 24, 39, 0.35)', 'rgba(17, 24, 39, 0.92)']}
            style={styles.heroGradient}
          >
            <View style={styles.heroBadge}>
              <Compass size={12} color="#FFFFFF" style={{ marginRight: 4 }} />
              <Text style={styles.heroBadgeText}>ARMADA RESMI PARIWISATA TJ</Text>
            </View>
            <Text style={styles.heroTitle}>Sewa Bus Rombongan &amp; Wisata</Text>
            <Text style={styles.heroSub}>
              Pilihan armada Jetbus 5 SHD, New Armada R25 &amp; Jetbus 3+ dengan suspensi udara empuk.
            </Text>
          </LinearGradient>
        </View>

        {/* Section 1: Pilihan Tipe Armada Bus */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>1. Pilih Tipe Armada Bus</Text>
          <Text style={styles.sectionSub}>Tersedia 4 Pilihan Unit</Text>
        </View>

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.busCardsScroll}
        >
          {BUS_OPTIONS.map((bus) => {
            const isSelected = selectedBus.id === bus.id;
            return (
              <TouchableOpacity
                key={bus.id}
                activeOpacity={0.85}
                onPress={() => setSelectedBus(bus)}
                style={[
                  styles.busOptionCard,
                  isSelected && styles.busOptionCardSelected,
                ]}
              >
                <Image source={bus.image} style={styles.busOptionImage} resizeMode="cover" />
                <View style={styles.busOptionContent}>
                  <Text style={styles.busOptionName} numberOfLines={1}>{bus.name}</Text>
                  <Text style={styles.busOptionBody} numberOfLines={1}>{bus.body}</Text>
                  <Text style={styles.busOptionCap}>{bus.capacity} • {bus.chassis}</Text>
                  <Text style={styles.busOptionPrice}>
                    Rp {bus.pricePerDay.toLocaleString('id-ID')} <Text style={styles.busOptionPriceSub}>/ hari</Text>
                  </Text>
                  <View style={[styles.selectIndicator, isSelected && styles.selectIndicatorActive]}>
                    <Text style={[styles.selectIndicatorText, isSelected && styles.selectIndicatorTextActive]}>
                      {isSelected ? '✓ Terpilih' : 'Pilih Unit'}
                    </Text>
                  </View>
                </View>
              </TouchableOpacity>
            );
          })}
        </ScrollView>

        {/* Section 2: Form Rincian Perjalanan */}
        <View style={styles.sectionHeader}>
          <Text style={styles.sectionTitle}>2. Detail Perjalanan</Text>
          <Text style={styles.sectionSub}>Lengkapi Rute &amp; Jadwal</Text>
        </View>

        <View style={styles.formCard}>
          {/* Pickup Location */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>LOKASI PENJEMPUTAN</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === 'pickup' && styles.inputContainerFocused,
              ]}
            >
              <MapPin size={18} color={focusedField === 'pickup' ? COLORS.brandRed : '#6B7280'} />
              <TextInput
                style={styles.textInput}
                value={pickup}
                onChangeText={setPickup}
                placeholder="Contoh: Pool Kuningan / Cirebon / Jakarta"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField('pickup')}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Destination */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>KOTA TUJUAN WISATA</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === 'destination' && styles.inputContainerFocused,
              ]}
            >
              <MapPin size={18} color={focusedField === 'destination' ? COLORS.brandRed : '#6B7280'} />
              <TextInput
                style={styles.textInput}
                value={destination}
                onChangeText={setDestination}
                placeholder="Contoh: Yogyakarta / Bandung / Bali / Malang"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField('destination')}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Start Date */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>TANGGAL KEBERANGKATAN</Text>
            <View
              style={[
                styles.inputContainer,
                focusedField === 'startDate' && styles.inputContainerFocused,
              ]}
            >
              <Calendar size={18} color={focusedField === 'startDate' ? COLORS.brandRed : '#6B7280'} />
              <TextInput
                style={styles.textInput}
                value={startDate}
                onChangeText={setStartDate}
                placeholder="YYYY-MM-DD (Contoh: 2026-09-15)"
                placeholderTextColor="#9CA3AF"
                onFocus={() => setFocusedField('startDate')}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>

          {/* Steppers: Days & Buses Count */}
          <View style={styles.countersRow}>
            {/* Days Count */}
            <View style={styles.counterBox}>
              <Text style={styles.counterLabel}>DURASI (HARI)</Text>
              <View style={styles.stepperContainer}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setDaysCount(Math.max(1, daysCount - 1))}
                  style={styles.stepBtn}
                >
                  <Minus size={16} color="#111827" />
                </TouchableOpacity>
                <Text style={styles.stepValueText}>{daysCount} Hari</Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setDaysCount(daysCount + 1)}
                  style={styles.stepBtn}
                >
                  <Plus size={16} color="#111827" />
                </TouchableOpacity>
              </View>
            </View>

            {/* Bus Count */}
            <View style={styles.counterBox}>
              <Text style={styles.counterLabel}>JUMLAH BUS</Text>
              <View style={styles.stepperContainer}>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setBusCount(Math.max(1, busCount - 1))}
                  style={styles.stepBtn}
                >
                  <Minus size={16} color="#111827" />
                </TouchableOpacity>
                <Text style={styles.stepValueText}>{busCount} Unit</Text>
                <TouchableOpacity
                  activeOpacity={0.7}
                  onPress={() => setBusCount(busCount + 1)}
                  style={styles.stepBtn}
                >
                  <Plus size={16} color="#111827" />
                </TouchableOpacity>
              </View>
            </View>
          </View>

          {/* Additional Notes */}
          <View style={styles.inputGroup}>
            <Text style={styles.inputLabel}>CATATAN / PERMINTAAN KHUSUS (OPSIONAL)</Text>
            <View
              style={[
                styles.textareaContainer,
                focusedField === 'notes' && styles.inputContainerFocused,
              ]}
            >
              <TextInput
                style={styles.textareaInput}
                value={notes}
                onChangeText={setNotes}
                placeholder="Contoh: Titik kumpul sekolah, butuh mikrofon karaoke, paket tol &amp; makan..."
                placeholderTextColor="#9CA3AF"
                multiline
                numberOfLines={3}
                onFocus={() => setFocusedField('notes')}
                onBlur={() => setFocusedField(null)}
              />
            </View>
          </View>
        </View>

        {/* Section 3: Ringkasan & Estimasi Biaya */}
        <View style={styles.summaryCard}>
          <Text style={styles.summaryHeading}>Estimasi Biaya Sewa</Text>
          <View style={styles.summaryLine}>
            <Text style={styles.summaryLabel}>Unit: {selectedBus.name}</Text>
            <Text style={styles.summaryVal}>Rp {selectedBus.pricePerDay.toLocaleString('id-ID')} / hari</Text>
          </View>
          <View style={styles.summaryLine}>
            <Text style={styles.summaryLabel}>Durasi &amp; Unit: {daysCount} Hari x {busCount} Bus</Text>
            <Text style={styles.summaryVal}>Rp {estimatedTotal.toLocaleString('id-ID')}</Text>
          </View>
          <View style={styles.summaryLine}>
            <Text style={[styles.summaryLabel, { color: '#059669' }]}>Driver &amp; Kru Resmi TJ</Text>
            <Text style={[styles.summaryVal, { color: '#059669' }]}>Termasuk</Text>
          </View>

          <View style={styles.summaryDivider} />

          <View style={styles.totalRow}>
            <View>
              <Text style={styles.totalLabel}>Total Estimasi Biaya</Text>
              <Text style={styles.totalSub}>*Belum termasuk tol, parkir &amp; tips sopir</Text>
            </View>
            <Text style={styles.totalPrice}>Rp {estimatedTotal.toLocaleString('id-ID')}</Text>
          </View>
        </View>

        {/* Security & Support Guarantee */}
        <View style={styles.guaranteeBox}>
          <ShieldCheck size={18} color="#059669" />
          <Text style={styles.guaranteeText}>
            Armada resmi PO Tunggal Jaya dengan asuransi Jasa Raharja &amp; uji KIR berkala.
          </Text>
        </View>

        <View style={{ height: 120 }} />
      </ScrollView>

      {/* Floating Bottom Action Bar */}
      <View style={styles.bottomBarWrapper}>
        <View style={styles.bottomBar}>
          <View>
            <Text style={styles.bottomBarLabel}>Total Estimasi ({daysCount} Hari)</Text>
            <Text style={styles.bottomBarPrice}>Rp {estimatedTotal.toLocaleString('id-ID')}</Text>
          </View>

          <TouchableOpacity
            style={[styles.submitBtn, loading && styles.submitBtnDisabled]}
            disabled={loading}
            onPress={handleSubmit}
            activeOpacity={0.85}
          >
            {loading ? (
              <ActivityIndicator color="#FFFFFF" />
            ) : (
              <Text style={styles.submitBtnText}>Ajukan Sewa &gt;</Text>
            )}
          </TouchableOpacity>
        </View>
      </View>
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
  heroCard: {
    height: 160,
    borderRadius: 22,
    overflow: 'hidden',
    borderWidth: 1,
    borderColor: '#E2E8F0',
    marginBottom: 22,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.08,
        shadowRadius: 12,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  heroImage: {
    width: '100%',
    height: '100%',
  },
  heroGradient: {
    ...StyleSheet.absoluteFillObject,
    padding: 18,
    justifyContent: 'flex-end',
  },
  heroBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    backgroundColor: '#2563EB',
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 8,
    marginBottom: 6,
  },
  heroBadgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 10,
    color: '#FFFFFF',
    letterSpacing: 0.5,
  },
  heroTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 18,
    color: '#FFFFFF',
    marginBottom: 4,
  },
  heroSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: 'rgba(255, 255, 255, 0.85)',
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  sectionTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 16,
    color: '#111827',
  },
  sectionSub: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: '#6B7280',
  },
  busCardsScroll: {
    gap: 14,
    paddingRight: 20,
    marginBottom: 22,
  },
  busOptionCard: {
    width: 240,
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    overflow: 'hidden',
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
  busOptionCardSelected: {
    borderColor: COLORS.brandRed,
    backgroundColor: '#FFFBFB',
  },
  busOptionImage: {
    width: '100%',
    height: 110,
  },
  busOptionContent: {
    padding: 14,
  },
  busOptionName: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#111827',
    marginBottom: 2,
  },
  busOptionBody: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 11,
    color: COLORS.brandRed,
    marginBottom: 4,
  },
  busOptionCap: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
    marginBottom: 8,
  },
  busOptionPrice: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 15,
    color: '#111827',
    marginBottom: 10,
  },
  busOptionPriceSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
  },
  selectIndicator: {
    backgroundColor: '#F1F4F8',
    paddingVertical: 7,
    borderRadius: 10,
    alignItems: 'center',
  },
  selectIndicatorActive: {
    backgroundColor: COLORS.brandRed,
  },
  selectIndicatorText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 12,
    color: '#4B5563',
  },
  selectIndicatorTextActive: {
    color: '#FFFFFF',
  },
  formCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 22,
    padding: 18,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    marginBottom: 20,
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
  inputGroup: {
    marginBottom: 14,
  },
  inputLabel: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: '#6B7280',
    marginBottom: 6,
    letterSpacing: 0.5,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#F1F4F8',
    borderRadius: 14,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    paddingHorizontal: 14,
    height: 50,
    gap: 10,
  },
  inputContainerFocused: {
    borderColor: COLORS.brandRed,
    backgroundColor: '#FFFFFF',
  },
  textInput: {
    flex: 1,
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 13,
    color: '#111827',
    paddingVertical: 0,
    ...(Platform.OS === 'web' ? ({ outlineStyle: 'none', borderWidth: 0 } as any) : {}),
  },
  countersRow: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 14,
  },
  counterBox: {
    flex: 1,
  },
  counterLabel: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 11,
    color: '#6B7280',
    marginBottom: 6,
    letterSpacing: 0.5,
  },
  stepperContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#F1F4F8',
    borderRadius: 14,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    padding: 6,
  },
  stepBtn: {
    width: 36,
    height: 36,
    borderRadius: 10,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#E2E8F0',
  },
  stepValueText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 13,
    color: '#111827',
  },
  textareaContainer: {
    backgroundColor: '#F1F4F8',
    borderRadius: 14,
    borderWidth: 1.5,
    borderColor: '#E2E8F0',
    padding: 12,
    minHeight: 70,
  },
  textareaInput: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 13,
    color: '#111827',
    ...(Platform.OS === 'web' ? ({ outlineStyle: 'none', borderWidth: 0 } as any) : {}),
  },
  summaryCard: {
    backgroundColor: '#FFFFFF',
    borderRadius: 20,
    padding: 18,
    borderWidth: 1,
    borderColor: '#E2E8F0',
    marginBottom: 16,
  },
  summaryHeading: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#111827',
    marginBottom: 12,
  },
  summaryLine: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  summaryLabel: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 13,
    color: '#4B5563',
  },
  summaryVal: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 13,
    color: '#111827',
  },
  summaryDivider: {
    height: 1,
    backgroundColor: '#E2E8F0',
    marginVertical: 10,
  },
  totalRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  totalLabel: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#111827',
  },
  totalSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 10,
    color: '#9CA3AF',
    marginTop: 2,
  },
  totalPrice: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 17,
    color: COLORS.brandRed,
  },
  guaranteeBox: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: 'rgba(5, 150, 105, 0.08)',
    borderRadius: 14,
    padding: 14,
    borderWidth: 1,
    borderColor: 'rgba(5, 150, 105, 0.2)',
  },
  guaranteeText: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: '#059669',
    flex: 1,
  },
  bottomBarWrapper: {
    position: 'absolute',
    bottom: Platform.OS === 'ios' ? 28 : 20,
    left: 20,
    right: 20,
  },
  bottomBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
    borderWidth: 1,
    borderColor: '#E2E8F0',
    paddingVertical: 12,
    paddingHorizontal: 18,
    borderRadius: 36,
    ...Platform.select({
      ios: {
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.12,
        shadowRadius: 18,
      },
      android: {
        elevation: 8,
      },
    }),
  },
  bottomBarLabel: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: '#6B7280',
  },
  bottomBarPrice: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 17,
    color: '#111827',
  },
  submitBtn: {
    backgroundColor: COLORS.brandRed,
    paddingVertical: 12,
    paddingHorizontal: 22,
    borderRadius: 24,
  },
  submitBtnDisabled: {
    opacity: 0.7,
  },
  submitBtnText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 14,
    color: '#FFFFFF',
  },
});
