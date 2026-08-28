import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  Modal,
  TouchableOpacity,
  ScrollView,
  Platform,
  ActivityIndicator,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import {
  Bell,
  X,
  Bus,
  Tag,
  Clock,
  ShieldCheck,
  CheckCheck,
  ChevronRight,
  ArrowRight,
} from "lucide-react-native";
import { COLORS } from "../theme/colors";
import apiClient from "../api/client";

export interface NotificationItem {
  id: string;
  title: string;
  message: string;
  type: "booking" | "promo" | "reminder" | "system" | string;
  booking_id?: number;
  is_read: boolean;
  created_at: string;
  created_at_human?: string;
}

interface NotificationModalProps {
  visible: boolean;
  onClose: () => void;
  onNavigateToBooking?: (bookingId?: number) => void;
  onNavigateToPromo?: () => void;
  onUpdateUnreadCount?: (count: number) => void;
}

export const NotificationModal: React.FC<NotificationModalProps> = ({
  visible,
  onClose,
  onNavigateToBooking,
  onNavigateToPromo,
  onUpdateUnreadCount,
}) => {
  const [notifications, setNotifications] = useState<NotificationItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [filterType, setFilterType] = useState<"all" | "booking" | "promo">(
    "all",
  );

  const fetchNotifications = async () => {
    try {
      setLoading(true);
      const res = await apiClient.get("/notifications").catch(() => null);
      if (res?.data?.data && Array.isArray(res.data.data)) {
        setNotifications(res.data.data);
        const unread = res.data.data.filter((n: any) => !n.is_read).length;
        if (onUpdateUnreadCount) onUpdateUnreadCount(unread);
      } else {
        // Fallback default notifications
        const defaults: NotificationItem[] = [
          {
            id: "1",
            title: "E-Tiket Terbit & Terkonfirmasi",
            message:
              "Tiket bus Resi Bisma rute Kuningan → Jakarta Kalideres Anda telah aktif dan siap digunakan.",
            type: "booking",
            is_read: false,
            created_at: new Date().toISOString(),
            created_at_human: "10 menit yang lalu",
          },
          {
            id: "2",
            title: "Voucher Diskon 10% Aktif",
            message:
              "Gunakan kode kupon TJBERKAH saat pemesanan tiket AKAP untuk mendapatkan potongan tarif.",
            type: "promo",
            is_read: false,
            created_at: new Date(Date.now() - 3600000).toISOString(),
            created_at_human: "1 jam yang lalu",
          },
          {
            id: "3",
            title: "Layanan Pool Pusat 24 Jam",
            message:
              "Customer Care dan bantuan operasional Pool Cilimus & Pool Cidahu siaga melayani Anda 24 jam.",
            type: "system",
            is_read: true,
            created_at: new Date(Date.now() - 86400000).toISOString(),
            created_at_human: "1 hari yang lalu",
          },
        ];
        setNotifications(defaults);
        if (onUpdateUnreadCount) onUpdateUnreadCount(2);
      }
    } catch {
      // ignore
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (visible) {
      fetchNotifications();
    }
  }, [visible]);

  const handleMarkAsRead = async (item: NotificationItem) => {
    const updated = notifications.map((n) =>
      n.id === item.id ? { ...n, is_read: true } : n,
    );
    setNotifications(updated);
    const unread = updated.filter((n) => !n.is_read).length;
    if (onUpdateUnreadCount) onUpdateUnreadCount(unread);

    try {
      await apiClient.post(`/notifications/${item.id}/read`).catch(() => {});
    } catch {}

    if (item.type === "booking" && onNavigateToBooking) {
      onClose();
      onNavigateToBooking(item.booking_id);
    } else if (item.type === "promo" && onNavigateToPromo) {
      onClose();
      onNavigateToPromo();
    }
  };

  const handleMarkAllAsRead = async () => {
    const updated = notifications.map((n) => ({ ...n, is_read: true }));
    setNotifications(updated);
    if (onUpdateUnreadCount) onUpdateUnreadCount(0);
    try {
      await apiClient.post("/notifications/read-all").catch(() => {});
    } catch {}
  };

  const filteredList = notifications.filter((item) => {
    if (filterType === "all") return true;
    return item.type === filterType;
  });

  const getIconForType = (type: string) => {
    switch (type) {
      case "booking":
        return <Bus size={18} color="#2563EB" />;
      case "promo":
        return <Tag size={18} color="#D97706" />;
      case "reminder":
        return <Clock size={18} color="#059669" />;
      default:
        return <ShieldCheck size={18} color="#7C3AED" />;
    }
  };

  const getBgForType = (type: string) => {
    switch (type) {
      case "booking":
        return "#EFF6FF";
      case "promo":
        return "#FFFBEB";
      case "reminder":
        return "#ECFDF5";
      default:
        return "#F5F3FF";
    }
  };

  return (
    <Modal
      visible={visible}
      animationType="slide"
      transparent={true}
      onRequestClose={onClose}
    >
      <View style={styles.modalOverlay}>
        <TouchableOpacity
          style={styles.modalBackdrop}
          activeOpacity={1}
          onPress={onClose}
        />

        <View style={styles.sheetContainer}>
          <SafeAreaView edges={["bottom"]}>
            {/* Header */}
            <View style={styles.sheetHeader}>
              <View>
                <View style={styles.titleRow}>
                  <Bell
                    size={18}
                    color={COLORS.brandBlue}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.sheetTitle}>Pusat Notifikasi</Text>
                </View>
                <Text style={styles.sheetSubtitle}>
                  Pemberitahuan tiket, jadwal & promo terbaru
                </Text>
              </View>

              <TouchableOpacity
                onPress={onClose}
                style={styles.closeBtn}
                activeOpacity={0.7}
              >
                <X size={18} color="#64748B" />
              </TouchableOpacity>
            </View>

            {/* Filter Chips & Mark All Read */}
            <View style={styles.filterRow}>
              <View style={styles.chipsContainer}>
                <TouchableOpacity
                  onPress={() => setFilterType("all")}
                  style={[
                    styles.filterChip,
                    filterType === "all" && styles.filterChipActive,
                  ]}
                >
                  <Text
                    style={[
                      styles.filterChipText,
                      filterType === "all" && styles.filterChipTextActive,
                    ]}
                  >
                    Semua
                  </Text>
                </TouchableOpacity>

                <TouchableOpacity
                  onPress={() => setFilterType("booking")}
                  style={[
                    styles.filterChip,
                    filterType === "booking" && styles.filterChipActive,
                  ]}
                >
                  <Text
                    style={[
                      styles.filterChipText,
                      filterType === "booking" && styles.filterChipTextActive,
                    ]}
                  >
                    Tiket & Trip
                  </Text>
                </TouchableOpacity>

                <TouchableOpacity
                  onPress={() => setFilterType("promo")}
                  style={[
                    styles.filterChip,
                    filterType === "promo" && styles.filterChipActive,
                  ]}
                >
                  <Text
                    style={[
                      styles.filterChipText,
                      filterType === "promo" && styles.filterChipTextActive,
                    ]}
                  >
                    Promo
                  </Text>
                </TouchableOpacity>
              </View>

              <TouchableOpacity
                onPress={handleMarkAllAsRead}
                style={styles.markAllBtn}
                activeOpacity={0.7}
              >
                <CheckCheck
                  size={14}
                  color={COLORS.brandBlue}
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.markAllText}>Baca Semua</Text>
              </TouchableOpacity>
            </View>

            {/* Content List */}
            <ScrollView
              style={styles.listScroll}
              showsVerticalScrollIndicator={false}
            >
              {loading ? (
                <View style={styles.centerLoading}>
                  <ActivityIndicator size="small" color={COLORS.brandBlue} />
                  <Text style={styles.loadingText}>Memuat notifikasi...</Text>
                </View>
              ) : filteredList.length === 0 ? (
                <View style={styles.emptyContainer}>
                  <Bell size={36} color="#CBD5E1" />
                  <Text style={styles.emptyTitle}>Belum Ada Notifikasi</Text>
                  <Text style={styles.emptyDesc}>
                    Semua kabar terbaru seputar tiket dan perjalanan Anda akan
                    tampil di sini.
                  </Text>
                </View>
              ) : (
                filteredList.map((item) => (
                  <TouchableOpacity
                    key={item.id}
                    activeOpacity={0.85}
                    onPress={() => handleMarkAsRead(item)}
                    style={[
                      styles.notifCard,
                      !item.is_read && styles.notifCardUnread,
                    ]}
                  >
                    <View
                      style={[
                        styles.notifIconBox,
                        { backgroundColor: getBgForType(item.type) },
                      ]}
                    >
                      {getIconForType(item.type)}
                    </View>

                    <View style={styles.notifContent}>
                      <View style={styles.notifHeaderRow}>
                        <Text style={styles.notifTitle} numberOfLines={1}>
                          {item.title}
                        </Text>
                        {!item.is_read && <View style={styles.unreadDot} />}
                      </View>

                      <Text style={styles.notifMessage}>{item.message}</Text>

                      <View style={styles.notifFooterRow}>
                        <Text style={styles.notifTime}>
                          {item.created_at_human || "Baru saja"}
                        </Text>

                        {item.type === "booking" && (
                          <View style={styles.actionPill}>
                            <Text style={styles.actionPillText}>Cek Tiket</Text>
                            <ArrowRight
                              size={10}
                              color={COLORS.brandBlue}
                              style={{ marginLeft: 2 }}
                            />
                          </View>
                        )}

                        {item.type === "promo" && (
                          <View style={styles.actionPill}>
                            <Text style={styles.actionPillText}>
                              Lihat Voucher
                            </Text>
                            <ArrowRight
                              size={10}
                              color={COLORS.brandBlue}
                              style={{ marginLeft: 2 }}
                            />
                          </View>
                        )}
                      </View>
                    </View>
                  </TouchableOpacity>
                ))
              )}
              <View style={{ height: 20 }} />
            </ScrollView>
          </SafeAreaView>
        </View>
      </View>
    </Modal>
  );
};

const styles = StyleSheet.create({
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.6)",
    justifyContent: "flex-end",
  },
  modalBackdrop: {
    flex: 1,
  },
  sheetContainer: {
    backgroundColor: "#FFFFFF",
    borderTopLeftRadius: 26,
    borderTopRightRadius: 26,
    maxHeight: "80%",
    paddingTop: 16,
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: -4 },
        shadowOpacity: 0.12,
        shadowRadius: 16,
      },
      android: {
        elevation: 10,
      },
    }),
  },
  sheetHeader: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingBottom: 14,
    borderBottomWidth: 1,
    borderBottomColor: "#F1F5F9",
  },
  titleRow: {
    flexDirection: "row",
    alignItems: "center",
  },
  sheetTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 17,
    color: "#0F172A",
  },
  sheetSubtitle: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#64748B",
    marginTop: 2,
  },
  closeBtn: {
    width: 34,
    height: 34,
    borderRadius: 17,
    backgroundColor: "#F1F5F9",
    justifyContent: "center",
    alignItems: "center",
  },
  filterRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#F8FAFC",
  },
  chipsContainer: {
    flexDirection: "row",
    gap: 6,
  },
  filterChip: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 10,
    backgroundColor: "#F1F5F9",
  },
  filterChipActive: {
    backgroundColor: COLORS.brandBlue,
  },
  filterChipText: {
    fontFamily: "PlusJakartaSans_600SemiBold",
    fontSize: 11,
    color: "#64748B",
  },
  filterChipTextActive: {
    color: "#FFFFFF",
    fontFamily: "PlusJakartaSans_700Bold",
  },
  markAllBtn: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 4,
  },
  markAllText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 11,
    color: COLORS.brandBlue,
  },
  listScroll: {
    paddingHorizontal: 20,
    paddingTop: 12,
  },
  centerLoading: {
    paddingVertical: 40,
    alignItems: "center",
  },
  loadingText: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#94A3B8",
    marginTop: 8,
  },
  emptyContainer: {
    paddingVertical: 50,
    alignItems: "center",
    justifyContent: "center",
  },
  emptyTitle: {
    fontFamily: "PlusJakartaSans_800ExtraBold",
    fontSize: 15,
    color: "#1E293B",
    marginTop: 12,
  },
  emptyDesc: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 12,
    color: "#94A3B8",
    textAlign: "center",
    marginTop: 4,
    maxWidth: 240,
    lineHeight: 18,
  },
  notifCard: {
    flexDirection: "row",
    padding: 14,
    borderRadius: 16,
    backgroundColor: "#FFFFFF",
    borderWidth: 1,
    borderColor: "#E2E8F0",
    marginBottom: 10,
  },
  notifCardUnread: {
    backgroundColor: "#F8FAFC",
    borderColor: "#BFDBFE",
  },
  notifIconBox: {
    width: 40,
    height: 40,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  notifContent: {
    flex: 1,
  },
  notifHeaderRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 2,
  },
  notifTitle: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 13,
    color: "#0F172A",
    flex: 1,
  },
  unreadDot: {
    width: 7,
    height: 7,
    borderRadius: 3.5,
    backgroundColor: "#2563EB",
    marginLeft: 6,
  },
  notifMessage: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 11.5,
    color: "#475569",
    lineHeight: 16,
    marginTop: 2,
  },
  notifFooterRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginTop: 8,
  },
  notifTime: {
    fontFamily: "PlusJakartaSans_500Medium",
    fontSize: 10,
    color: "#94A3B8",
  },
  actionPill: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#EFF6FF",
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
  },
  actionPillText: {
    fontFamily: "PlusJakartaSans_700Bold",
    fontSize: 9.5,
    color: COLORS.brandBlue,
  },
});
