part of 'pages.dart';

abstract class Routes {
  Routes._();

  static const homePage = _Paths.homePage;
  static const loginPage = _Paths.loginPage;
  static const doAssignmentScreen = _Paths.doAssignmentScreen;
  static const teacherDashboard = _Paths.teacherDashboard;
}

abstract class _Paths {
  static const homePage = '/';
  static const loginPage = '/login';
  static const doAssignmentScreen = '/do-assignment';
  static const teacherDashboard = '/teacher-dashboard';
}