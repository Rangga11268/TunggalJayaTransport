import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Share,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import {
  ArrowLeft,
  Share2,
  QrCode,
  Bus,
  CheckCircle2,
  Calendar,
  Clock,
  User,
  Armchair,
} from 'lucide-react-native';

export default function TicketDetailScreen({ navigation, route }: any) {
  const insets = useSafeAreaInsets();
  const { booking, schedule, selectedSeats } = route.params || {};

  const bookingCode = booking?.booking_code || booking?.id || 'TJ-782941';

  const onShare = async () => {
    try {
      await Share.share({
        message: `E-Tiket Tunggal Jaya Transport\nKode: ${bookingCode}\nRute: ${schedule?.route?.origin_city || 'Kuningan'} - ${schedule?.route?.destination_city || 'Jakarta'}\nKursi: ${selectedSeats?.join(', ') || '1A'}\nStatus: Lunas & Terkonfirmasi`,
      });
    } catch {}
  };

  return (
    <View style={styles.container}>
      {/* Top Header */}
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <TouchableOpacity
          style={styles.backBtn}
          onPress={() => navigation.navigate('MainTabs')}
          activeOpacity={0.7}
        >
          <ArrowLeft size={18} color="#FFFFFF" />
        </TouchableOpacity>

        <Text style={styles.headerTitle}>E-Tiket Resmi</Text>

        <TouchableOpacity
          style={styles.backBtn}
          onPress={onShare}
          activeOpacity={0.7}
        >
          <Share2 size={18} color="#FFFFFF" />
        </TouchableOpacity>
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 60 }}
        showsVerticalScrollIndicator={false}
      >
        {/* Boarding Pass Card */}
        <View style={styles.boardingPass}>
          {/* Top Pass Header */}
          <View style={styles.passHeader}>
            <View style={styles.passBrand}>
              <Image
                source={require('../../assets/logo/logoNoBg.png')}
                style={styles.passLogo}
                resizeMode="contain"
              />
              <Text style={styles.passBrandText}>Tunggal Jaya Transport</Text>
            </View>
            <View style={styles.confirmedBadge}>
              <CheckCircle2 size={12} color={Colors.success} style={{ marginRight: 4 }} />
              <Text style={styles.confirmedText}>LUNAS</Text>
            </View>
          </View>

          {/* Route Section */}
          <View style={styles.routeSection}>
            <View>
              <Text style={styles.routeCityLabel}>ASAL</Text>
              <Text style={styles.routeCityName}>
                {schedule?.route?.origin_city || 'Kuningan'}
              </Text>
              <Text style={styles.routeTime}>
                {schedule?.departure_time?.slice(0, 5) || '07:00'} WIB
              </Text>
            </View>

            <View style={styles.routeBusIcon}>
              <Bus size={20} color={Colors.primary} />
              <Text style={styles.routeArrowText}>➔</Text>
            </View>

            <View style={{ alignItems: 'flex-end' }}>
              <Text style={styles.routeCityLabel}>TUJUAN</Text>
              <Text style={styles.routeCityName}>
                {schedule?.route?.destination_city || 'Jakarta'}
              </Text>
              <Text style={styles.routeTime}>
                {schedule?.arrival_time?.slice(0, 5) || '12:00'} WIB
              </Text>
            </View>
          </View>

          {/* Tear Line / Notches */}
          <View style={styles.tearLineContainer}>
            <View style={styles.leftNotch} />
            <View style={styles.dashedLine} />
            <View style={styles.rightNotch} />
          </View>

          {/* Detail Info Grid */}
          <View style={styles.passDetails}>
            <View style={styles.detailCol}>
              <Text style={styles.detailLabel}>NAMA PENUMPANG</Text>
              <Text style={styles.detailVal}>
                {booking?.passenger_name || 'Rangga Putra'}
              </Text>
            </View>
            <View style={styles.detailCol}>
              <Text style={styles.detailLabel}>NOMOR KURSI</Text>
              <Text style={[styles.detailVal, { color: Colors.primary }]}>
                {selectedSeats?.join(', ') || booking?.seat_numbers?.join(', ') || '1A'}
              </Text>
            </View>
          </View>

          <View style={styles.passDetails}>
            <View style={styles.detailCol}>
              <Text style={styles.detailLabel}>ARMADA BUS</Text>
              <Text style={styles.detailVal}>
                {schedule?.bus?.name || 'Executive Suite Class'}
              </Text>
            </View>
            <View style={styles.detailCol}>
              <Text style={styles.detailLabel}>KODE BOOKING</Text>
              <Text style={styles.detailVal}>{bookingCode}</Text>
            </View>
          </View>

          {/* QR Code Section */}
          <View style={styles.qrSection}>
            <View style={styles.qrPlaceholder}>
              <QrCode size={110} color={Colors.primary} />
            </View>
            <Text style={styles.qrInstruction}>
              Tunjukkan QR Code ini ke petugas saat naik bus di terminal / pool keberangkatan.
            </Text>
          </View>
        </View>

        {/* Back to Home Button */}
        <TouchableOpacity
          style={styles.homeBtn}
          onPress={() => navigation.navigate('MainTabs')}
          activeOpacity={0.8}
        >
          <Text style={styles.homeBtnText}>Kembali ke Beranda</Text>
        </TouchableOpacity>
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
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingBottom: 14,
    backgroundColor: Colors.surfaceCard,
    borderBottomWidth: 1.2,
    borderBottomColor: Colors.border,
  },
  backBtn: {
    width: 40,
    height: 40,
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.full,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  headerTitle: {
    fontSize: 16,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  boardingPass: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 28,
    borderWidth: 1.5,
    borderColor: Colors.border,
    marginTop: 20,
    overflow: 'hidden',
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.5,
    shadowRadius: 28,
    elevation: 8,
  },
  passHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
    backgroundColor: '#181824',
  },
  passBrand: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  passLogo: {
    width: 22,
    height: 22,
    marginRight: 8,
  },
  passBrandText: {
    color: '#FFFFFF',
    fontWeight: '900',
    fontSize: 13,
  },
  confirmedBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.successContainer,
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: Radius.pill,
  },
  confirmedText: {
    color: Colors.success,
    fontSize: 11,
    fontWeight: '900',
  },
  routeSection: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 22,
  },
  routeCityLabel: {
    color: Colors.textMuted,
    fontSize: 10,
    fontWeight: '800',
  },
  routeCityName: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '900',
    marginTop: 2,
  },
  routeTime: {
    color: Colors.primary,
    fontSize: 13,
    fontWeight: '700',
    marginTop: 2,
  },
  routeBusIcon: {
    alignItems: 'center',
  },
  routeArrowText: {
    color: Colors.textMuted,
    fontSize: 12,
  },
  tearLineContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    position: 'relative',
    marginVertical: 4,
  },
  leftNotch: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: Colors.background,
    marginLeft: -10,
  },
  rightNotch: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: Colors.background,
    marginRight: -10,
  },
  dashedLine: {
    flex: 1,
    height: 1,
    borderWidth: 1,
    borderColor: Colors.borderLight,
    borderStyle: 'dashed',
  },
  passDetails: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: 22,
    paddingVertical: 12,
  },
  detailCol: {
    flex: 1,
  },
  detailLabel: {
    color: Colors.textMuted,
    fontSize: 10,
    fontWeight: '800',
  },
  detailVal: {
    color: '#FFFFFF',
    fontSize: 13,
    fontWeight: '800',
    marginTop: 3,
  },
  qrSection: {
    alignItems: 'center',
    padding: 24,
    backgroundColor: '#101018',
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
  qrPlaceholder: {
    backgroundColor: '#FFFFFF',
    padding: 14,
    borderRadius: 16,
    marginBottom: 14,
  },
  qrInstruction: {
    color: Colors.textSecondary,
    fontSize: 11,
    textAlign: 'center',
    lineHeight: 16,
  },
  homeBtn: {
    height: 52,
    backgroundColor: Colors.surfaceCard,
    borderRadius: Radius.pill,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 20,
    borderWidth: 1.2,
    borderColor: Colors.border,
  },
  homeBtnText: {
    color: '#FFFFFF',
    fontSize: 15,
    fontWeight: '800',
  },
});
