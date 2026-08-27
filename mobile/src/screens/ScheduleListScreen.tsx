import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Image,
  Dimensions,
  ActivityIndicator,
  FlatList,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import api from '../api/client';
import {
  ArrowLeft,
  Bell,
  Star,
  Bus,
  Clock,
  ChevronRight,
  ShieldCheck,
  Tag,
  Info,
  CheckCircle,
} from 'lucide-react-native';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function ScheduleListScreen({ navigation, route }: any) {
  const insets = useSafeAreaInsets();
  const [activeTab, setActiveTab] = useState('deals');
  const [schedules, setSchedules] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedSchedule, setSelectedSchedule] = useState<any>(null);

  const tabs = [
    { id: 'deals', title: 'Deals', icon: Tag },
    { id: 'details', title: 'Details', icon: Info },
    { id: 'reviews', title: 'Reviews', icon: Star },
  ];

  useEffect(() => {
    fetchSchedules();
  }, []);

  const fetchSchedules = async () => {
    try {
      setLoading(true);
      const res = await api.get('/schedules');
      const data = res.data?.data || [];
      setSchedules(data);
      if (data.length > 0) {
        setSelectedSchedule(data[0]);
      }
    } catch (e) {
      console.error('Error loading schedules:', e);
    } finally {
      setLoading(false);
    }
  };

  const isBusDeparted = (departureTimeStr: string) => {
    try {
      if (!departureTimeStr) return false;
      const parts = departureTimeStr.split(':');
      if (parts.length < 2) return false;
      const now = new Date();
      const dep = new Date();
      dep.setHours(parseInt(parts[0], 10), parseInt(parts[1], 10), 0, 0);
      return now > dep;
    } catch {
      return false;
    }
  };

  return (
    <View style={styles.container}>
      <ScrollView
        contentContainerStyle={{ paddingBottom: 120 }}
        showsVerticalScrollIndicator={false}
      >
        {/* Top Hero Preview Header */}
        <View style={styles.heroHeaderContainer}>
          <Image
            source={require('../../assets/images/heroImg.jpg')}
            style={styles.heroHeaderImg}
            resizeMode="cover"
          />
          <View style={styles.heroHeaderOverlay} />

          {/* Top Bar Nav */}
          <View style={[styles.topBar, { top: insets.top + 12 }]}>
            <TouchableOpacity
              style={styles.navCircleBtn}
              onPress={() => navigation.goBack()}
              activeOpacity={0.7}
            >
              <ArrowLeft size={18} color="#FFFFFF" />
            </TouchableOpacity>

            <View style={styles.brandCapsule}>
              <Text style={styles.brandCapsuleText}>PILIH JADWAL</Text>
            </View>

            <TouchableOpacity style={styles.navCircleBtn} activeOpacity={0.7}>
              <Bell size={18} color="#FFFFFF" />
            </TouchableOpacity>
          </View>
        </View>

        {/* Highlighted Route Summary Card */}
        <View style={styles.summaryCard}>
          <View style={styles.summaryTopRow}>
            <View>
              <Text style={styles.summaryRoute}>Kuningan ➔ Jakarta</Text>
              <View style={styles.ratingRow}>
                <Star size={13} color={Colors.gold} fill={Colors.gold} />
                <Text style={styles.ratingText}> 4.8 </Text>
                <Text style={styles.reviewsCount}>(1.2k ulasan)</Text>
              </View>
            </View>

            <View style={styles.priceContainer}>
              <Text style={styles.priceSmall}>Mulai</Text>
              <Text style={styles.priceLarge}>
                Rp{' '}
                {Number(selectedSchedule?.price || 130000).toLocaleString('id-ID')}
              </Text>
            </View>
          </View>

          {/* Segmented Tab Switcher Pills */}
          <View style={styles.tabsContainer}>
            {tabs.map((tab) => {
              const isSelected = activeTab === tab.id;
              const TabIcon = tab.icon;
              return (
                <TouchableOpacity
                  key={tab.id}
                  style={[styles.tabPill, isSelected && styles.tabPillActive]}
                  onPress={() => setActiveTab(tab.id)}
                  activeOpacity={0.8}
                >
                  <TabIcon
                    size={13}
                    color={isSelected ? '#FFFFFF' : Colors.textSecondary}
                    style={{ marginRight: 6 }}
                  />
                  <Text
                    style={[styles.tabPillText, isSelected && styles.tabPillTextActive]}
                  >
                    {tab.title}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </View>

          {/* Tab Content Panels */}
          {activeTab === 'deals' && (
            <View style={styles.tabContentBox}>
              <Tag size={16} color={Colors.primary} style={{ marginRight: 8 }} />
              <Text style={styles.dealPromoText}>
                Gunakan kupon <Text style={{ color: Colors.primary, fontWeight: '900' }}>TJHEMAT20</Text> untuk diskon Rp 20.000
              </Text>
            </View>
          )}

          {activeTab === 'details' && (
            <View style={styles.detailsBox}>
              <Text style={styles.detailItemText}>• Bus Executive Suite Class 2-2</Text>
              <Text style={styles.detailItemText}>• Fasilitas: AC, Reclining Seat, Audio, Snack</Text>
              <Text style={styles.detailItemText}>• Waktu tempuh perkiraan: 4 - 5 Jam via Tol Cipali</Text>
            </View>
          )}

          {activeTab === 'reviews' && (
            <View style={styles.reviewsBox}>
              <Text style={styles.reviewUser}>Rangga P. (⭐⭐⭐⭐⭐)</Text>
              <Text style={styles.reviewText}>
                "Bus bersih dan supir sangat profesional, sampai tepat waktu!"
              </Text>
            </View>
          )}
        </View>

        {/* Schedule List */}
        <View style={styles.schedulesSection}>
          <Text style={styles.schedulesTitle}>Jadwal Keberangkatan Hari Ini</Text>

          {loading ? (
            <ActivityIndicator color={Colors.primary} style={{ marginTop: 24 }} />
          ) : (
            schedules.map((item, idx) => {
              const departed = isBusDeparted(item.departure_time);
              const isCurrent = selectedSchedule?.id === item.id;

              return (
                <TouchableOpacity
                  key={idx}
                  style={[
                    styles.scheduleItemCard,
                    isCurrent && styles.scheduleItemCardSelected,
                  ]}
                  onPress={() => setSelectedSchedule(item)}
                  activeOpacity={0.85}
                >
                  <Image
                    source={require('../../assets/images/heroImg.jpg')}
                    style={styles.busThumbnail}
                    resizeMode="cover"
                  />

                  <View style={styles.itemInfo}>
                    <View style={styles.itemHeaderRow}>
                      <Text style={styles.itemBusName}>
                        {item.bus?.name || 'Tunggal Jaya Suite'}
                      </Text>
                      <View
                        style={[
                          styles.badgeSmall,
                          departed ? styles.badgeSmallDeparted : styles.badgeSmallAvailable,
                        ]}
                      >
                        <Text
                          style={[
                            styles.badgeSmallText,
                            departed ? styles.badgeSmallDepartedText : styles.badgeSmallAvailableText,
                          ]}
                        >
                          {departed ? 'Berangkat' : 'Tersedia'}
                        </Text>
                      </View>
                    </View>

                    <Text style={styles.itemTime}>
                      {item.departure_time ? item.departure_time.slice(0, 5) : '07:00'} -{' '}
                      {item.arrival_time ? item.arrival_time.slice(0, 5) : '12:00'}
                    </Text>

                    <View style={styles.itemFooterRow}>
                      <Text style={styles.itemPrice}>
                        Rp {Number(item.price || 130000).toLocaleString('id-ID')}
                      </Text>
                      <ChevronRight size={16} color={Colors.textSecondary} />
                    </View>
                  </View>
                </TouchableOpacity>
              );
            })
          )}
        </View>
      </ScrollView>

      {/* Floating Bottom Booking Bar (Matching Phone 3 from screenshot) */}
      <View
        style={[
          styles.bottomFloatingBar,
          { paddingBottom: Math.max(insets.bottom + 8, 16) },
        ]}
      >
        <View style={styles.bottomBarContent}>
          <View>
            <Text style={styles.bottomBarLabel}>Total Estimasi (1 Kursi)</Text>
            <Text style={styles.bottomBarPrice}>
              Rp{' '}
              {Number(selectedSchedule?.price || 130000).toLocaleString('id-ID')}
            </Text>
          </View>

          <TouchableOpacity
            style={[
              styles.bookNowBtn,
              isBusDeparted(selectedSchedule?.departure_time) && styles.bookNowBtnDisabled,
            ]}
            disabled={isBusDeparted(selectedSchedule?.departure_time)}
            onPress={() =>
              navigation.navigate('SeatSelection', {
                schedule: selectedSchedule,
                date: new Date().toISOString().split('T')[0],
              })
            }
            activeOpacity={0.85}
          >
            <Text style={styles.bookNowBtnText}>
              {isBusDeparted(selectedSchedule?.departure_time)
                ? 'Sudah Berangkat'
                : 'Book Now'}
            </Text>
          </TouchableOpacity>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.background,
  },
  heroHeaderContainer: {
    height: 190,
    width: '100%',
    position: 'relative',
  },
  heroHeaderImg: {
    width: '100%',
    height: '100%',
  },
  heroHeaderOverlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(11, 11, 15, 0.45)',
  },
  topBar: {
    position: 'absolute',
    left: 20,
    right: 20,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    zIndex: 10,
  },
  navCircleBtn: {
    width: 40,
    height: 40,
    backgroundColor: 'rgba(18, 18, 24, 0.85)',
    borderRadius: Radius.full,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.12)',
  },
  brandCapsule: {
    backgroundColor: 'rgba(18, 18, 24, 0.85)',
    paddingHorizontal: 14,
    paddingVertical: 7,
    borderRadius: Radius.pill,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.12)',
  },
  brandCapsuleText: {
    color: '#FFFFFF',
    fontWeight: '900',
    fontSize: 11,
    letterSpacing: 0.5,
  },
  summaryCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 28,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 22,
    marginHorizontal: 20,
    marginTop: -36,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.5,
    shadowRadius: 28,
    elevation: 8,
  },
  summaryTopRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 16,
  },
  summaryRoute: {
    fontSize: 18,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  ratingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 4,
  },
  ratingText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '800',
  },
  reviewsCount: {
    color: Colors.textSecondary,
    fontSize: 11,
  },
  priceContainer: {
    alignItems: 'flex-end',
  },
  priceSmall: {
    color: Colors.textMuted,
    fontSize: 10,
  },
  priceLarge: {
    color: Colors.primary,
    fontSize: 18,
    fontWeight: '900',
  },
  tabsContainer: {
    flexDirection: 'row',
    backgroundColor: Colors.surfaceContainer,
    borderRadius: Radius.pill,
    padding: 4,
    marginBottom: 16,
  },
  tabPill: {
    flex: 1,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 8,
    borderRadius: Radius.pill,
  },
  tabPillActive: {
    backgroundColor: Colors.primary,
  },
  tabPillText: {
    color: Colors.textSecondary,
    fontSize: 12,
    fontWeight: '700',
  },
  tabPillTextActive: {
    color: '#FFFFFF',
    fontWeight: '800',
  },
  tabContentBox: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    padding: 12,
    borderRadius: Radius.md,
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  dealPromoText: {
    color: '#FFFFFF',
    fontSize: 12,
    flex: 1,
  },
  detailsBox: {
    backgroundColor: Colors.surfaceContainer,
    padding: 12,
    borderRadius: Radius.md,
    gap: 4,
  },
  detailItemText: {
    color: Colors.textSecondary,
    fontSize: 12,
    lineHeight: 18,
  },
  reviewsBox: {
    backgroundColor: Colors.surfaceContainer,
    padding: 12,
    borderRadius: Radius.md,
  },
  reviewUser: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '800',
    marginBottom: 2,
  },
  reviewText: {
    color: Colors.textSecondary,
    fontSize: 12,
    fontStyle: 'italic',
  },
  schedulesSection: {
    paddingHorizontal: 20,
    marginTop: 22,
  },
  schedulesTitle: {
    fontSize: 16,
    fontWeight: '800',
    color: '#FFFFFF',
    marginBottom: 14,
  },
  scheduleItemCard: {
    flexDirection: 'row',
    backgroundColor: Colors.surfaceCard,
    borderRadius: 20,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 14,
    marginBottom: 12,
    alignItems: 'center',
  },
  scheduleItemCardSelected: {
    borderColor: Colors.primary,
    backgroundColor: '#1E1A22',
  },
  busThumbnail: {
    width: 68,
    height: 68,
    borderRadius: 14,
    marginRight: 14,
  },
  itemInfo: {
    flex: 1,
  },
  itemHeaderRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  itemBusName: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '800',
  },
  badgeSmall: {
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: Radius.pill,
  },
  badgeSmallText: {
    fontSize: 10,
    fontWeight: '800',
  },
  badgeSmallAvailable: {
    backgroundColor: Colors.successContainer,
  },
  badgeSmallAvailableText: {
    color: Colors.success,
    fontSize: 10,
    fontWeight: '800',
  },
  badgeSmallDeparted: {
    backgroundColor: Colors.surfaceContainer,
  },
  badgeSmallDepartedText: {
    color: Colors.textMuted,
    fontSize: 10,
    fontWeight: '700',
  },
  itemTime: {
    color: Colors.textSecondary,
    fontSize: 12,
    marginTop: 3,
    fontWeight: '600',
  },
  itemFooterRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 6,
  },
  itemPrice: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '900',
  },
  bottomFloatingBar: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: Colors.surfaceCard,
    borderTopWidth: 1.2,
    borderTopColor: Colors.border,
    paddingHorizontal: 22,
    paddingTop: 14,
  },
  bottomBarContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  bottomBarLabel: {
    color: Colors.textMuted,
    fontSize: 11,
  },
  bottomBarPrice: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '900',
  },
  bookNowBtn: {
    backgroundColor: Colors.primary,
    paddingHorizontal: 26,
    paddingVertical: 12,
    borderRadius: Radius.pill,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.4,
    shadowRadius: 14,
    elevation: 6,
  },
  bookNowBtnDisabled: {
    backgroundColor: Colors.surfaceHighest,
  },
  bookNowBtnText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '900',
  },
});
