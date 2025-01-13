import 'package:nega_lms/utils/imports.dart';

class ClassPointOverviewScreen extends GetView<ClassDetailController> {
  const ClassPointOverviewScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 2,
            blurRadius: 5,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: SingleChildScrollView(
          child: DataTable(
            headingRowHeight: 60,
            dataRowMinHeight: 56,
            dataRowMaxHeight: 56,
            headingTextStyle: const TextStyle(
              fontSize: 16,
              fontFamily: FontStyleTextStrings.bold,
              color: CustomColors.primary,
            ),
            dataTextStyle: const TextStyle(
              fontSize: 14,
              fontFamily: FontStyleTextStrings.medium,
              color: CustomColors.primaryText,
            ),
            horizontalMargin: 24,
            columnSpacing: 40,
            border: TableBorder(
              borderRadius: BorderRadius.circular(16),
              horizontalInside: const BorderSide(color: CustomColors.border, width: 1),
              verticalInside: const BorderSide(color: CustomColors.border, width: 1),
            ),
            headingRowColor:  WidgetStateProperty.all(CustomColors.background),
            columns: [
              const DataColumn(
                label: Expanded(
                  child: Text(
                    'Tên',
                    textAlign: TextAlign.center,
                  ),
                ),
              ),
              ...controller.assignmentNameList.map(
                (assignment) => DataColumn(
                  label: Expanded(
                    child: Text(
                      assignment,
                      textAlign: TextAlign.center,
                    ),
                  ),
                ),
              ),
            ],
            rows: [
              ...controller.classPointList.map(
                (student) => DataRow(
                  cells: [
                    DataCell(
                      Center(
                        child: Text(student['name']),
                      ),
                    ),
                    ...List<DataCell>.from(
                      (student['points'] as List).map(
                        (point) => DataCell(
                          Center(
                            child: Text(point['score'].toString()),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
