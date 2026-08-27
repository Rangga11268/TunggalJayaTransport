import React, { useState, useEffect } from 'react';
import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  RefreshControl,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Colors, Radius } from '../theme/colors';
import api from '../api/client';
} from "react-native";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import { Colors, Radius } from "../theme/colors";
import api from "../api/client";
import {
  Calendar,
  Bus,
  Sparkles,
  Compass,
  ChevronRight,
  Receipt,
  CheckCircle2,
  Clock,
} from 'lucide-react-native';
} from "lucide-react-native";

export default function BookingHistoryScreen({ navigation }: any) {
  const insets = useSafeAreaInsets();
  const [activeTab, setActiveTab] = useState<'akap' | 'charter'>('akap');
  const [activeTab, setActiveTab] = useState<"akap" | "charter">("akap");
  const [bookings, setBookings] = useState<any[]>([]);
  const [charters, setCharters] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [bookingsRes, chartersRes] = await Promise.all([
        api.get('/bookings').catch(() => ({ data: { data: [] } })),
        api.get('/charter/history').catch(() => ({ data: { data: [] } })),
        api.get("/bookings").catch(() => ({ data: { data: [] } })),
        api.get("/charter/history").catch(() => ({ data: { data: [] } })),
      ]);

      setBookings(bookingsRes.data?.data || []);
      setCharters(chartersRes.data?.data || []);
    } catch (e) {
      console.error('Error fetching history:', e);
      console.error("Error fetching history:", e);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={[styles.topHeader, { paddingTop: insets.top + 10 }]}>
        <Text style={styles.headerTitle}>Riwayat Pesanan</Text>
      </View>

      {/* Tabs */}
      <View style={styles.tabsContainer}>
        <TouchableOpacity
          style={[styles.tabBtn, activeTab === 'akap' && styles.tabBtnActive]}
          onPress={() => setActiveTab('akap')}
          style={[styles.tabBtn, activeTab === "akap" && styles.tabBtnActive]}
          onPress={() => setActiveTab("akap")}
          activeOpacity={0.8}
        >
          <Bus
            size={14}
            color={activeTab === 'akap' ? '#FFFFFF' : Colors.textSecondary}
            color={activeTab === "akap" ? "#FFFFFF" : Colors.textSecondary}
            style={{ marginRight: 6 }}
          />
          <Text
            style={[
              styles.tabBtnText,
              activeTab === 'akap' && styles.tabBtnTextActive,
              activeTab === "akap" && styles.tabBtnTextActive,
            ]}
          >
            Bus Reguler AKAP
          </Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.tabBtn, activeTab === 'charter' && styles.tabBtnActive]}
          onPress={() => setActiveTab('charter')}
          style={[
            styles.tabBtn,
            activeTab === "charter" && styles.tabBtnActive,
          ]}
          onPress={() => setActiveTab("charter")}
          activeOpacity={0.8}
        >
          <Sparkles
          <Compass
            size={14}
            color={activeTab === 'charter' ? '#FFFFFF' : Colors.textSecondary}
            color={activeTab === "charter" ? "#FFFFFF" : Colors.textSecondary}
            style={{ marginRight: 6 }}
          />
          <Text
            style={[
              styles.tabBtnText,
              activeTab === 'charter' && styles.tabBtnTextActive,
              activeTab === "charter" && styles.tabBtnTextActive,
            ]}
          >
            Sewa Pariwisata
          </Text>
        </TouchableOpacity>
      </View>

      <ScrollView
        contentContainerStyle={{ paddingHorizontal: 20, paddingBottom: 110 }}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={() => {
              setRefreshing(true);
              fetchData();
            }}
            tintColor={Colors.primary}
          />
        }
      >
        {loading ? (
          <ActivityIndicator color={Colors.primary} style={{ marginTop: 30 }} />
        ) : activeTab === 'akap' ? (
        ) : activeTab === "akap" ? (
          bookings.length > 0 ? (
            bookings.map((item, idx) => (
              <TouchableOpacity
                key={idx}
                style={styles.historyCard}
                onPress={() =>
                  navigation.navigate('TicketDetail', {
                  navigation.navigate("TicketDetail", {
                    booking: item,
                    schedule: item.schedule,
                    selectedSeats: item.seat_numbers,
                  })
                }
                activeOpacity={0.85}
              >
                <View style={styles.cardHeader}>
                  <Text style={styles.bookingCode}>
                    {item.booking_code || `TJ-BK${idx + 101}`}
                  </Text>
                  <View style={styles.statusPill}>
                    <Text style={styles.statusText}>
                      {item.payment_status?.toUpperCase() || 'LUNAS'}
                      {item.payment_status?.toUpperCase() || "LUNAS"}
                    </Text>
                  </View>
                </View>

                <Text style={styles.routeText}>
                  {item.schedule?.route?.origin_city || 'Kuningan'} ➔{' '}
                  {item.schedule?.route?.destination_city || 'Jakarta'}
                  {item.schedule?.route?.origin_city || "Kuningan"} ➔{" "}
                  {item.schedule?.route?.destination_city || "Jakarta"}
                </Text>

                <View style={styles.cardFooter}>
                  <Text style={styles.dateText}>
                    {item.departure_date || '2026-08-27'}
                    {item.departure_date || "2026-08-27"}
                  </Text>
                  <Text style={styles.priceText}>
                    Rp {Number(item.total_amount || 130000).toLocaleString('id-ID')}
                    Rp{" "}
                    {Number(item.total_amount || 130000).toLocaleString(
                      "id-ID",
                    )}
                  </Text>
                </View>
              </TouchableOpacity>
            ))
          ) : (
            <View style={styles.emptyBox}>
              <Receipt size={48} color={Colors.textMuted} />
              <Text style={styles.emptyTitle}>Belum Ada Tiket AKAP</Text>
              <Text style={styles.emptyDesc}>
                Tiket bus reguler yang Anda pesan akan muncul di sini.
              </Text>
            </View>
          )
        ) : charters.length > 0 ? (
          charters.map((item, idx) => (
            <View key={idx} style={styles.historyCard}>
              <View style={styles.cardHeader}>
                <Text style={styles.bookingCode}>
                  {item.charter_code || `TJ-PAR${idx + 1}`}
                </Text>
                <View style={styles.statusPill}>
                  <Text style={styles.statusText}>
                    {item.status?.toUpperCase() || 'MENUNGGU KONFIRMASI'}
                    {item.status?.toUpperCase() || "MENUNGGU KONFIRMASI"}
                  </Text>
                </View>
              </View>
              <Text style={styles.routeText}>
                {item.pickup_location || 'Kuningan'} ➔ {item.destination || 'Bandung'}
                {item.pickup_location || "Kuningan"} ➔{" "}
                {item.destination || "Bandung"}
              </Text>
              <View style={styles.cardFooter}>
                <Text style={styles.dateText}>
                  {item.start_date || '2026-09-01'} ({item.bus_count || 1} Unit)
                  {item.start_date || "2026-09-01"} ({item.bus_count || 1} Unit)
                </Text>
                <Text style={styles.priceText}>
                  Rp {Number(item.total_price || 3500000).toLocaleString('id-ID')}
                  Rp{" "}
                  {Number(item.total_price || 3500000).toLocaleString("id-ID")}
                </Text>
              </View>
            </View>
          ))
        ) : (
          <View style={styles.emptyBox}>
            <Sparkles size={48} color={Colors.textMuted} />
            <Compass size={48} color={Colors.textMuted} />
            <Text style={styles.emptyTitle}>Belum Ada Sewa Pariwisata</Text>
            <Text style={styles.emptyDesc}>
              Pengajuan sewa bus rombongan akan muncul di sini.
            </Text>
          </View>
        )}
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
    fontWeight: "900",
    color: "#FFFFFF",
  },
  tabsContainer: {
    flexDirection: 'row',
    flexDirection: "row",
    backgroundColor: Colors.surfaceCard,
    paddingHorizontal: 20,
    paddingVertical: 10,
    gap: 10,
  },
  tabBtn: {
    flex: 1,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    paddingVertical: 10,
    borderRadius: Radius.pill,
    backgroundColor: Colors.surfaceContainer,
    borderWidth: 1,
    borderColor: Colors.borderLight,
  },
  tabBtnActive: {
    backgroundColor: Colors.primary,
    borderColor: Colors.primary,
  },
  tabBtnText: {
    color: Colors.textSecondary,
    fontSize: 12,
    fontWeight: '700',
    fontWeight: "700",
  },
  tabBtnTextActive: {
    color: '#FFFFFF',
    fontWeight: '800',
    color: "#FFFFFF",
    fontWeight: "800",
  },
  historyCard: {
    backgroundColor: Colors.surfaceCard,
    borderRadius: 20,
    borderWidth: 1.2,
    borderColor: Colors.border,
    padding: 16,
    marginTop: 12,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 8,
  },
  bookingCode: {
    color: Colors.textMuted,
    fontSize: 11,
    fontWeight: '800',
    fontWeight: "800",
  },
  statusPill: {
    backgroundColor: Colors.successContainer,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: Radius.pill,
  },
  statusText: {
    color: Colors.success,
    fontSize: 10,
    fontWeight: '800',
    fontWeight: "800",
  },
  routeText: {
    color: '#FFFFFF',
    color: "#FFFFFF",
    fontSize: 15,
    fontWeight: '800',
    fontWeight: "800",
    marginBottom: 10,
  },
  cardFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: Colors.border,
  },
  dateText: {
    color: Colors.textSecondary,
    fontSize: 12,
  },
  priceText: {
    color: Colors.primary,
    fontSize: 14,
    fontWeight: '900',
    fontWeight: "900",
  },
  emptyBox: {
    alignItems: 'center',
    alignItems: "center",
    marginTop: 60,
    paddingHorizontal: 30,
  },
  emptyTitle: {
    color: '#FFFFFF',
    color: "#FFFFFF",
    fontSize: 16,
    fontWeight: '800',
    fontWeight: "800",
    marginTop: 16,
  },
  emptyDesc: {
    color: Colors.textSecondary,
    fontSize: 12,
    textAlign: 'center',
    textAlign: "center",
    marginTop: 6,
    lineHeight: 18,
  },
});
