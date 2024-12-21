part of 'pages.dart';

abstract class Routes {
  Routes._();

  static const homePage = _Paths.homePage;
  static const loginPage = _Paths.loginPage;
  static const classListPage = _Paths.classListPage;
  static const classDetailScreen = _Paths.classDetailScreen;
  static const doAssignmentScreen = _Paths.doAssignmentScreen;
  static const teacherDashboard = _Paths.teacherDashboard;
}

abstract class _Paths {
  static const homePage = '/';
  static const loginPage = '/login';
  static const classListPage = '/class-list';
  static const classDetailScreen = '/class-detail';
  static const doAssignmentScreen = '/do-assignment';
  static const teacherDashboard = '/teacher-dashboard';
}