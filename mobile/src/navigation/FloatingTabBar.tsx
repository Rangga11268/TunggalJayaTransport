import React from 'react';
import { View, TouchableOpacity, StyleSheet, Dimensions } from 'react-native';
import { Colors, Radius } from '../theme/colors';
import { Home, Calendar, HelpCircle, User } from 'lucide-react-native';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function FloatingTabBar({ state, descriptors, navigation }: any) {
  const icons = [Home, Calendar, HelpCircle, User];

  return (
    <View style={styles.floatingContainer}>
      <View style={styles.tabBarCard}>
        {state.routes.map((route: any, index: number) => {
          const { options } = descriptors[route.key];
          const isFocused = state.index === index;
          const IconComp = icons[index] || Home;

          const onPress = () => {
            const event = navigation.emit({
              type: 'tabPress',
              target: route.key,
              canPreventDefault: true,
            });

            if (!isFocused && !event.defaultPrevented) {
              navigation.navigate(route.name);
            }
          };

          return (
            <TouchableOpacity
              key={index}
              onPress={onPress}
              style={[
                styles.tabItem,
                isFocused && styles.tabItemActive,
              ]}
              activeOpacity={0.8}
            >
              <IconComp
                size={20}
                color={isFocused ? '#FFFFFF' : '#8E8EA8'}
              />
            </TouchableOpacity>
          );
        })}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  floatingContainer: {
    position: 'absolute',
    bottom: 20,
    left: 28,
    right: 28,
    alignItems: 'center',
    zIndex: 99,
  },
  tabBarCard: {
    flexDirection: 'row',
    backgroundColor: '#14141E',
    borderRadius: Radius.pill,
    height: 64,
    width: '100%',
    justifyContent: 'space-around',
    alignItems: 'center',
    paddingHorizontal: 10,
    borderWidth: 1.2,
    borderColor: '#262638',
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.6,
    shadowRadius: 28,
    elevation: 12,
  },
  tabItem: {
    width: 42,
    height: 42,
    borderRadius: Radius.full,
    justifyContent: 'center',
    alignItems: 'center',
  },
  tabItemActive: {
    width: 48,
    height: 48,
    backgroundColor: Colors.primary,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.45,
    shadowRadius: 14,
    elevation: 8,
  },
});
