import 'package:nega_lms/utils/imports.dart';

part 'routes.dart';

class Pages {
  Pages._();

  static const initialRoute = Routes.assignmentListPage;

  static final routes = [
    GetPage(
      name: _Paths.homePage,
      page: () => const HomeScreen(),
      binding: HomeBinding(),
    ),
    GetPage(
      name: _Paths.loginPage,
      page: () => const LoginScreen(),
      binding: LoginBinding(),
    ),
    GetPage(
      name: _Paths.classListPage,
      page: () => ClassListScreen(),
      binding: ClassBinding(),
    ),
    GetPage(
      name: _Paths.assignmentListPage,
      page: () => const AssignmentListScreen(),
      binding: AssignmentBinding(),
    ),
    GetPage(
      name: _Paths.doAssignmentScreen,
      page: () => const DoAssignmentScreen(),
      binding: AssignmentBinding(),
    ),
  ];
}
