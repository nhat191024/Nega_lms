import 'package:nega_lms/utils/imports.dart';

class StorageService {
  static final box = GetStorage();

  static writeStringData({
    required String key,
    required String value,
  }) {
    box.write(key, value);
  }

    static dynamic readData({
    required String key,
  }) {
    return box.read(key);
  }

    static bool checkData({
    required String key,
  }) {
    return box.hasData(key);
  }

    static removeData({
    required String key,
  }) {
    box.remove(key);
  }
}
