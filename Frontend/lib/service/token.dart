import 'package:nega_lms/utils/imports.dart';

class Token {
  static dynamic getToken() async {
    if (StorageService.checkData(key: LocalStorageKeys.token)) {
      return StorageService.readData(key: LocalStorageKeys.token);
    } else {
      return null;
    }
  }

  static Future<bool> checkToken() async {
    if (StorageService.checkData(key: LocalStorageKeys.token)) {
      var url = Uri.parse("${Api.server}token-check");
      var token = StorageService.readData(key: LocalStorageKeys.token);
      try {
        var response = await get(url, headers: {
          "Authorization": "Bearer $token",
        });
        if (response.statusCode == 200) {
          return true;
        } else {
          Get.offAllNamed(Routes.loginPage);
          return false;
        }
      } catch (e) {
        Get.offAllNamed(Routes.loginPage);
        return false;
      }
    } else {
      Get.offAllNamed(Routes.loginPage);
      return false;
    }
  }

  static storeToken(String token) async {
    if (StorageService.checkData(key: LocalStorageKeys.token)) {
      StorageService.removeData(key: LocalStorageKeys.token);
    }

    StorageService.writeStringData(
      key: LocalStorageKeys.token,
      value: token,
    );
  }
}
