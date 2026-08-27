import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  Platform,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { LinearGradient } from 'expo-linear-gradient';
import { useNavigation, useRoute } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import {
  ArrowLeft,
  Bell,
  Star,
  ChevronRight,
  Clock,
  MapPin,
  Sparkles,
  Tag,
  Bus,
  Search,
} from 'lucide-react-native';
import { RootStackParamList } from '../navigation/RootNavigator';
import { COLORS } from '../theme/colors';
import apiClient from '../api/client';

const { width } = Dimensions.get('window');

type NavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function ScheduleListScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<any>();

  const [schedules, setSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedRouteFilter, setSelectedRouteFilter] = useState('all');

  useEffect(() => {
    fetchSchedules();
  }, []);

  const fetchSchedules = async () => {
    try {
      setLoading(true);
      const res = await apiClient.get('/schedules');
      const list = Array.isArray(res.data) ? res.data : res.data.data || [];
      setSchedules(list);
    } catch (e) {
      console.log('Error fetching schedules:', e);
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchSchedules();
    setRefreshing(false);
  };

  return (
    <View style={styles.container}>
      {/* Top App Bar */}
      <SafeAreaView edges={['top']} style={styles.topBar}>
        <View style={styles.topBarContent}>
          <View>
            <Text style={styles.topBarTitle}>Jadwal Keberangkatan</Text>
            <Text style={styles.topBarSub}>Armada Eksekutif Tunggal Jaya</Text>
          </View>

          <TouchableOpacity activeOpacity={0.7} style={styles.iconBtn}>
            <Bell size={18} color="#FFFFFF" />
          </TouchableOpacity>
        </View>
      </SafeAreaView>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={COLORS.brandRed} />
        }
      >
        {/* Route Banner Header Card */}
        <View style={styles.bannerCard}>
          <Image
            source={require('../../assets/images/heroImg.jpg')}
            style={styles.bannerImage}
            resizeMode="cover"
          />
          <LinearGradient
            colors={['rgba(10, 12, 16, 0.4)', 'rgba(10, 12, 16, 0.95)']}
            style={styles.bannerGradient}
          >
            <View style={styles.bannerBadge}>
              <Text style={styles.bannerBadgeText}>AKAP Regular Line</Text>
            </View>
            <Text style={styles.bannerTitle}>Pilih Rute &amp; Unit Bus</Text>
            <Text style={styles.bannerSubtitle}>
              Klik kartu unit untuk melihat detail spesifikasi, foto interior &amp; pilih kursi.
            </Text>
          </LinearGradient>
        </View>

        {/* Schedule List Items */}
        <View style={styles.listSection}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionHeading}>Daftar Unit Tersedia ({schedules.length})</Text>
            <Text style={styles.sectionSub}>Realtime Schedule</Text>
          </View>

          {loading ? (
            <ActivityIndicator size="large" color={COLORS.brandRed} style={{ marginVertical: 40 }} />
          ) : (
            schedules.map((item, idx) => {
              const busName = item.bus?.name || 'Resi Bisma';
              const busType = item.bus?.bus_type || 'Executive Class';
              const origin = item.route?.origin || 'Jakarta';
              const destination = item.route?.destination || 'Kuningan';
              const depTime = item.departure_time ? item.departure_time.substring(0, 5) : '07:00';
              const arrTime = item.arrival_time ? item.arrival_time.substring(0, 5) : '15:00';
              const price = Number(item.price || 180000).toLocaleString('id-ID');

              // Map photo based on unit name
              const nameLower = (busName || '').toLowerCase();
              let thumbSource = require('../../assets/images/resiBisma.webp');
              if (nameLower.includes('primadona')) thumbSource = require('../../assets/images/primadona.webp');
              if (nameLower.includes('bentas')) thumbSource = require('../../assets/images/bentas01.webp');
              if (nameLower.includes('kyloren')) thumbSource = require('../../assets/images/kylorenParwis.webp');

              return (
                <TouchableOpacity
                  key={item.id || idx}
                  activeOpacity={0.85}
                  onPress={() => navigation.navigate('ScheduleDetail', { scheduleId: item.id })}
                  style={styles.scheduleCard}
                >
                  <Image source={thumbSource} style={styles.scheduleImage} />

                  <View style={styles.scheduleMiddle}>
                    <View style={styles.busHeaderRow}>
                      <Text style={styles.scheduleTitle}>{busName}</Text>
                      <View style={styles.ratingBadge}>
                        <Text style={styles.ratingBadgeText}>★ 4.8</Text>
                      </View>
                    </View>

                    <Text style={styles.scheduleRouteText}>
                      <MapPin size={12} color={COLORS.textMuted} /> {origin} ↔ {destination}
                    </Text>

                    <Text style={styles.scheduleTimeText}>
                      <Clock size={12} color={COLORS.brandRed} /> {depTime} - {arrTime} WIB
                    </Text>

                    <View style={styles.priceRow}>
                      <Text style={styles.schedulePriceText}>
                        Rp {price} <Text style={styles.schedulePriceSub}>/ Seat</Text>
                      </Text>
                    </View>
                  </View>

                  <View style={styles.chevronBox}>
                    <ChevronRight size={18} color="#FFFFFF" />
                  </View>
                </TouchableOpacity>
              );
            })
          )}
        </View>

        {/* Bottom spacer so list scrolls comfortably above the Floating Tab Bar */}
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
  topBar: {
    backgroundColor: COLORS.bgSurface,
    borderBottomWidth: 1,
    borderBottomColor: 'rgba(255, 255, 255, 0.08)',
  },
  topBarContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 12,
  },
  topBarTitle: {
    fontFamily: 'PlusJakartaSans_800ExtraBold',
    fontSize: 20,
    color: '#FFFFFF',
    letterSpacing: -0.3,
  },
  topBarSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: COLORS.textSecondary,
    marginTop: 2,
  },
  iconBtn: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#161922',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
  },
  scrollContent: {
    paddingHorizontal: 20,
    paddingTop: 16,
  },
  bannerCard: {
    height: 140,
    borderRadius: 20,
    overflow: 'hidden',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
    marginBottom: 20,
  },
  bannerImage: {
    width: '100%',
    height: '100%',
  },
  bannerGradient: {
    ...StyleSheet.absoluteFillObject,
    padding: 16,
    justifyContent: 'flex-end',
  },
  bannerBadge: {
    alignSelf: 'flex-start',
    backgroundColor: COLORS.brandRed,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
    marginBottom: 6,
  },
  bannerBadgeText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 10,
    color: '#FFFFFF',
  },
  bannerTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 16,
    color: '#FFFFFF',
    marginBottom: 2,
  },
  bannerSubtitle: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: COLORS.textSecondary,
  },
  listSection: {
    gap: 12,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  sectionHeading: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
  },
  sectionSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 12,
    color: COLORS.textMuted,
  },
  scheduleCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#14171F',
    borderRadius: 18,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.08)',
    padding: 12,
    gap: 14,
  },
  scheduleImage: {
    width: 74,
    height: 74,
    borderRadius: 14,
  },
  scheduleMiddle: {
    flex: 1,
  },
  busHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 4,
  },
  scheduleTitle: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
  },
  ratingBadge: {
    backgroundColor: 'rgba(255, 184, 0, 0.15)',
    paddingHorizontal: 6,
    paddingVertical: 2,
    borderRadius: 6,
  },
  ratingBadgeText: {
    fontFamily: 'PlusJakartaSans_600SemiBold',
    fontSize: 10,
    color: '#FFB800',
  },
  scheduleRouteText: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: COLORS.textSecondary,
    marginBottom: 4,
  },
  scheduleTimeText: {
    fontFamily: 'PlusJakartaSans_500Medium',
    fontSize: 12,
    color: COLORS.textSecondary,
    marginBottom: 6,
  },
  priceRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  schedulePriceText: {
    fontFamily: 'PlusJakartaSans_700Bold',
    fontSize: 15,
    color: '#FFFFFF',
  },
  schedulePriceSub: {
    fontFamily: 'PlusJakartaSans_400Regular',
    fontSize: 11,
    color: COLORS.textMuted,
  },
  chevronBox: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: COLORS.brandRed,
    justifyContent: 'center',
    alignItems: 'center',
  },
});
