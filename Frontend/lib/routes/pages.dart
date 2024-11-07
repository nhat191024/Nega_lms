import 'package:nega_lms/utils/imports.dart';

part 'routes.dart';

class Pages {
  Pages._();

  static const initialRoute = Routes.homePage;

  static final routes = [
    GetPage(
      name: _Paths.homePage,
      page: () => const HomeScreen(),
      binding: HomeBinding(),
    ),
  ];
}