import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import api from '../api/client';
import {
  ArrowLeft,
  AlertTriangle,
  Check,
  Armchair,
  Disc,
} from 'lucide-react-native';

export default function SeatSelectionScreen({ navigation, route }: any) {
  const insets = useSafeAreaInsets();
  const { schedule, date } = route.params || {};

  const [selectedSeats, setSelectedSeats] = useState<string[]>([]);
  const [occupiedSeats, setOccupiedSeats] = useState<string[]>(['1A', '2B', '4C']);
  const [loading, setLoading] = useState(false);

  // Check if departed
  const isDeparted = () => {
    try {
      if (!schedule?.departure_time) return false;
      const parts = schedule.departure_time.split(':');
      if (parts.length < 2) return false;
      const now = new Date();
      const dep = new Date();
      dep.setHours(parseInt(parts[0], 10), parseInt(parts[1], 10), 0, 0);
      return now > dep;
    } catch {
      return false;
    }
  };

  const departed = isDeparted();

  // 30 seats standard 2-2 layout (8 rows)
  const rows = [
    { row: '1', left: ['1A', '1B'], right: ['1C', '1D'] },
    { row: '2', left: ['2A', '2B'], right: ['2C', '2D'] },
    { row: '3', left: ['3A', '3B'], right: ['3C', '3D'] },
    { row: '4', left: ['4A', '4B'], right: ['4C', '4D'] },
    { row: '5', left: ['5A', '5B'], right: ['5C', '5D'] },
    { row: '6', left: ['6A', '6B'], right: ['6C', '6D'] },
    { row: '7', left: ['7A', '7B'], right: ['7C', '7D'] },
    { row: '8', left: ['8A', '8B'], right: ['8C', '8D'] },
  ];

  const toggleSeat = (seatId: string) => {
    if (departed) {
      Alert.alert('Bus Sudah Berangkat', 'Perjalanan ini sudah berangkat hari ini.');
      return;
    }
    if (occupiedSeats.includes(seatId)) return;

    if (selectedSeats.includes(seatId)) {
      setSelectedSeats(selectedSeats.filter((s) => s !== seatId));
    } else {
      if (selectedSeats.length >= 4) {
        Alert.alert('Maksimal 4 Kursi', 'Maksimal pemesanan adalah 4 kursi sekaligus.');
        return;
      }
      setSelectedSeats([...selectedSeats, seatId]);
    }
  };

  const unitPrice = Number(schedule?.price || 130000);
  const totalPrice = selectedSeats.length * unitPrice;

  return (
    <View style={styles.container}>
      {/* Top Header Bar */}
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <TouchableOpacity
          style={styles.backBtn}
          onPress={() => navigation.goBack()}
          activeOpacity={0.7}
        >
          <ArrowLeft size={18} color="#FFFFFF" />
        </TouchableOpacity>

        <View style={styles.headerTitleCol}>
          <Text style={styles.headerTitle}>Pilih Kursi Bus</Text>
          <Text style={styles.headerSubtitle}>
            {schedule?.route?.origin_city || 'Kuningan'} ➔{' '}
            {schedule?.route?.destination_city || 'Jakarta'}
          </Text>
        </View>

        <View style={{ width: 40 }} />
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 130 }}
        showsVerticalScrollIndicator={false}
      >
        {/* Departed Warning Banner */}
        {departed && (
          <View style={styles.departedBanner}>
            <AlertTriangle size={18} color="#F59E0B" style={{ marginRight: 8 }} />
            <Text style={styles.departedBannerText}>
              Bus ini sudah berangkat ({schedule?.departure_time?.slice(0, 5)} WIB). Pemilihan kursi ditutup.
            </Text>
          </View>
        )}

        {/* Legend */}
        <View style={styles.legendCard}>
          <View style={styles.legendItem}>
            <View style={[styles.legendBox, { backgroundColor: Colors.surfaceContainer }]} />
            <Text style={styles.legendText}>Tersedia</Text>
          </View>
          <View style={styles.legendItem}>
            <View style={[styles.legendBox, { backgroundColor: Colors.primary }]} />
            <Text style={styles.legendText}>Dipilih</Text>
          </View>
          <View style={styles.legendItem}>
            <View style={[styles.legendBox, { backgroundColor: '#2E2E3E' }]} />
            <Text style={styles.legendText}>Terisi</Text>
          </View>
        </View>

        {/* Bus Cabin Outline */}
        <View style={styles.busCabin}>
          {/* Driver Area */}
          <View style={styles.driverSection}>
            <View style={styles.driverBadge}>
              <Disc size={18} color={Colors.textSecondary} style={{ marginRight: 6 }} />
              <Text style={styles.driverText}>AREA SUPIR</Text>
            </View>
            <View style={styles.doorBadge}>
              <Text style={styles.doorText}>PINTU</Text>
            </View>
          </View>

          <View style={styles.cabinDivider} />

          {/* Seat Grid */}
          {rows.map((r) => (
            <View key={r.row} style={styles.seatRow}>
              {/* Left 2 seats */}
              <View style={styles.seatPair}>
                {r.left.map((seatId) => {
                  const isSelected = selectedSeats.includes(seatId);
                  const isOccupied = occupiedSeats.includes(seatId);

                  return (
                    <TouchableOpacity
                      key={seatId}
                      style={[
                        styles.seatBox,
                        isSelected && styles.seatSelected,
                        isOccupied && styles.seatOccupied,
                      ]}
                      onPress={() => toggleSeat(seatId)}
                      disabled={isOccupied || departed}
                      activeOpacity={0.8}
                    >
                      {isSelected ? (
                        <Check size={14} color="#FFFFFF" />
                      ) : (
                        <Text
                          style={[
                            styles.seatText,
                            isOccupied && styles.seatTextOccupied,
                          ]}
                        >
                          {seatId}
                        </Text>
                      )}
                    </TouchableOpacity>
                  );
                })}
              </View>

              {/* Aisle */}
              <View style={styles.aisle}>
                <Text style={styles.aisleText}>{r.row}</Text>
              </View>

              {/* Right 2 seats */}
              <View style={styles.seatPair}>
                {r.right.map((seatId) => {
                  const isSelected = selectedSeats.includes(seatId);
                  const isOccupied = occupiedSeats.includes(seatId);

                  return (
                    <TouchableOpacity
                      key={seatId}
                      style={[
                        styles.seatBox,
                        isSelected && styles.seatSelected,
                        isOccupied && styles.seatOccupied,
                      ]}
                      onPress={() => toggleSeat(seatId)}
                      disabled={isOccupied || departed}
                      activeOpacity={0.8}
                    >
                      {isSelected ? (
                        <Check size={14} color="#FFFFFF" />
                      ) : (
                        <Text
                          style={[
                            styles.seatText,
                            isOccupied && styles.seatTextOccupied,
                          ]}
                        >
                          {seatId}
                        </Text>
                      )}
                    </TouchableOpacity>
                  );
                })}
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Floating Bottom Action Bar */}
      <View
        style={[
          styles.bottomFloatingBar,
          { paddingBottom: Math.max(insets.bottom + 8, 16) },
        ]}
      >
        <View style={styles.bottomBarContent}>
          <View>
            <Text style={styles.bottomBarLabel}>
              {selectedSeats.length > 0
                ? `${selectedSeats.join(', ')} (${selectedSeats.length} Kursi)`
                : 'Pilih Kursi'}
            </Text>
            <Text style={styles.bottomBarPrice}>
              Rp {totalPrice.toLocaleString('id-ID')}
            </Text>
          </View>

          <TouchableOpacity
            style={[
              styles.checkoutBtn,
              (selectedSeats.length === 0 || departed) && styles.checkoutBtnDisabled,
            ]}
            disabled={selectedSeats.length === 0 || departed}
            onPress={() =>
              navigation.navigate('Checkout', {
                schedule,
                selectedSeats,
                totalPrice,
                date,
              })
            }
            activeOpacity={0.85}
          >
            <Text style={styles.checkoutBtnText}>Lanjutkan</Text>
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
  headerTitleCol: {
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 16,
    fontWeight: '900',
    color: '#FFFFFF',
  },
  headerSubtitle: {
    fontSize: 12,
    color: Colors.textSecondary,
    marginTop: 2,
  },
  departedBanner: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(245, 158, 11, 0.15)',
    borderWidth: 1.2,
    borderColor: 'rgba(245, 158, 11, 0.4)',
    padding: 12,
    borderRadius: Radius.md,
    marginTop: 14,
  },
  departedBannerText: {
    color: '#FCD34D',
    fontSize: 12,
    fontWeight: '700',
    flex: 1,
  },
  legendCard: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    backgroundColor: Colors.surfaceCard,
    paddingVertical: 14,
    borderRadius: Radius.pill,
    borderWidth: 1.2,
    borderColor: Colors.border,
    marginVertical: 16,
  },
  legendItem: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  legendBox: {
    width: 16,
    height: 16,
    borderRadius: 4,
    marginRight: 6,
  },
  legendText: {
    color: Colors.textSecondary,
    fontSize: 12,
    fontWeight: '600',
  },
  busCabin: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 32,
    borderWidth: 1.5,
    borderColor: Colors.border,
    padding: 20,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.5,
    shadowRadius: 28,
    elevation: 8,
  },
  driverSection: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  driverBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surfaceContainer,
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: Radius.pill,
  },
  driverText: {
    color: Colors.textSecondary,
    fontSize: 11,
    fontWeight: '800',
  },
  doorBadge: {
    backgroundColor: Colors.surfaceContainer,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: Radius.pill,
  },
  doorText: {
    color: Colors.textMuted,
    fontSize: 10,
    fontWeight: '700',
  },
  cabinDivider: {
    height: 1.5,
    backgroundColor: Colors.border,
    marginBottom: 18,
  },
  seatRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  seatPair: {
    flexDirection: 'row',
    gap: 8,
  },
  seatBox: {
    width: 44,
    height: 44,
    backgroundColor: Colors.surfaceContainer,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  seatSelected: {
    backgroundColor: Colors.primary,
    borderColor: Colors.primary,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.5,
    shadowRadius: 10,
    elevation: 6,
  },
  seatOccupied: {
    backgroundColor: '#1C1C26',
    borderColor: '#262638',
  },
  seatText: {
    color: '#FFFFFF',
    fontSize: 12,
    fontWeight: '800',
  },
  seatTextOccupied: {
    color: '#4B4B60',
  },
  aisle: {
    width: 30,
    alignItems: 'center',
  },
  aisleText: {
    color: Colors.textMuted,
    fontSize: 11,
    fontWeight: '700',
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
  checkoutBtn: {
    backgroundColor: Colors.primary,
    paddingHorizontal: 26,
    paddingVertical: 12,
    borderRadius: Radius.pill,
  },
  checkoutBtnDisabled: {
    backgroundColor: Colors.surfaceHighest,
  },
  checkoutBtnText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '900',
  },
});
