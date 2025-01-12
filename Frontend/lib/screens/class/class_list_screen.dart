import 'package:nega_lms/utils/imports.dart';

class ClassListScreen extends GetView<ClassController> {
  final classControllers = Get.put(ClassController());
  final LayoutController layoutController = Get.find<LayoutController>();
  ClassListScreen({super.key}) {
    controller.searchController.addListener(() {
      if (controller.searchController.text.isNotEmpty) {
        controller.classFilter(controller.searchController.text);
      } else {
        controller.filteredList.value = controller.classList;
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: ResponsiveLayout(
        mobile: buildMobile(),
        desktop: buildDesktop(),
      ),
    );
  }

  //class card builder
  Widget classCardBuilder(String title, String description, int id, bool isJoined, List<String> tags, double verticalPadding, bool isLast) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: verticalPadding),
      child: Column(
        children: [
          GestureDetector(
            onTap: () {
              layoutController.sidebarController.selectIndex(99);
              Get.find<LayoutController>().goToClassDetail(id);
            },
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        title,
                        style: const TextStyle(
                          fontSize: 16,
                          color: CustomColors.primaryText,
                          fontFamily: FontStyleTextStrings.medium,
                        ),
                      ),
                      const SizedBox(height: 5),
                      Text(
                        description,
                        style: const TextStyle(
                          fontSize: 14,
                          color: CustomColors.secondaryText,
                          fontFamily: FontStyleTextStrings.regular,
                        ),
                      ),
                      const SizedBox(height: 20),
                      Wrap(
                        children: tags
                            .map(
                              (tag) => Container(
                                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                                margin: const EdgeInsets.only(right: 10),
                                decoration: BoxDecoration(
                                  color: CustomColors.primary,
                                  borderRadius: BorderRadius.circular(50),
                                ),
                                child: Text(
                                  tag,
                                  style: const TextStyle(
                                    fontSize: 12,
                                    color: CustomColors.background,
                                    fontFamily: FontStyleTextStrings.medium,
                                  ),
                                ),
                              ),
                            )
                            .toList(),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),
          if (!isLast) const Divider(),
        ],
      ),
    );
  }

  Widget buildDesktop() {
    return SingleChildScrollView(
      child: Padding(
        padding: const EdgeInsets.fromLTRB(40, 30, 200, 0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                  decoration: BoxDecoration(
                    color: Colors.transparent,
                    borderRadius: BorderRadius.circular(50),
                    border: Border.all(color: CustomColors.primaryText),
                  ),
                  child: const Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.filter_list, color: CustomColors.primaryText),
                      SizedBox(width: 5),
                      Text(
                        'Lọc',
                        style: TextStyle(
                          fontSize: 14,
                          color: CustomColors.primaryText,
                        ),
                      ),
                    ],
                  ),
                ),
                Obx(
                  () => Text(
                    '${controller.filteredList.length} Kết quả',
                    style: const TextStyle(
                      fontSize: 14,
                      color: CustomColors.primary,
                      fontFamily: FontStyleTextStrings.medium,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                SizedBox(
                  width: Get.width * 0.15,
                  child: Column(
                    children: [
                      SearchTextField(
                        textController: controller.searchController,
                        onSearch: (String query) {},
                        hintText: 'Tìm kiếm...',
                        color: CustomColors.background,
                        prefixColor: CustomColors.primaryText,
                      ),
                      CheckBoxField(
                        onChanged: (value) {},
                        title: 'Lập trình',
                        value: false.obs,
                        topPadding: 10,
                      ),
                      CheckBoxField(
                        onChanged: (value) {},
                        title: 'Kinh tế',
                        value: false.obs,
                        topPadding: 10,
                      ),
                      CheckBoxField(
                        onChanged: (value) {},
                        title: 'Toán học',
                        value: false.obs,
                        topPadding: 10,
                      ),
                    ],
                  ),
                ),
                const SizedBox(width: 20),
                Expanded(
                  child: Column(
                    children: [
                      Obx(
                        () => SizedBox(
                          height: Get.height * 0.8,
                          child: controller.isLoading.value
                              ? const Center(
                                  child: CircularProgressIndicator(),
                                )
                              : ListView.builder(
                                  shrinkWrap: true,
                                  physics: const AlwaysScrollableScrollPhysics(),
                                  itemCount: controller.filteredList.length,
                                  itemBuilder: (context, index) {
                                    return classCardBuilder(
                                      controller.filteredList[index].name ?? '',
                                      controller.filteredList[index].description ?? '',
                                      controller.filteredList[index].id ?? 0,
                                      controller.filteredList[index].isJoined ?? false,
                                      controller.filteredList[index].categories ?? [],
                                      10,
                                      index == controller.filteredList.length - 1,
                                    );
                                  },
                                ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget buildMobile() {
    return SingleChildScrollView(
      child: Column(
        children: [
          Obx(
            () => SizedBox(
              height: Get.height * 0.8,
              child: controller.isLoading.value
                  ? const Center(
                      child: CircularProgressIndicator(),
                    )
                  : ListView.builder(
                      shrinkWrap: true,
                      physics: const AlwaysScrollableScrollPhysics(),
                      itemCount: controller.filteredList.length,
                      itemBuilder: (context, index) {
                        return classCardBuilder(
                          controller.filteredList[index].name ?? '',
                          controller.filteredList[index].description ?? '',
                          controller.filteredList[index].id ?? 0,
                          controller.filteredList[index].isJoined ?? false,
                          controller.filteredList[index].categories ?? [],
                          10,
                          index == controller.filteredList.length - 1,
                        );
                      },
                    ),
            ),
          ),
        ],
      ),
    );
  }
}
